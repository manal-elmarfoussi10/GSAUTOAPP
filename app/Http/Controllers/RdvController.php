<?php

namespace App\Http\Controllers;

use App\Models\Rdv;
use App\Models\Poseur;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RdvController extends Controller
{
    public function calendar()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
    
        $stats = [
            'today' => Rdv::whereDate('start_time', $today)->count(),
            'this_week' => Rdv::whereBetween('start_time', [$startOfWeek, $endOfWeek])->count(),
            'completed' => Rdv::where('end_time', '<', now())->count(),
        ];
    
        $poseurs = Poseur::all();
    
        return view('rdv.calendar', compact('stats', 'poseurs'));
    }

    public function events(Request $request)
    {
        try {
            $start = Carbon::parse($request->input('start'))->toDateTimeString();
            $end = Carbon::parse($request->input('end'))->toDateTimeString();
            
            $events = Rdv::whereBetween('start_time', [$start, $end])
                ->with(['client', 'poseur'])
                ->get()
                ->map(function ($rdv) {
                    return [
                        'id' => $rdv->id,
                        'title' => ($rdv->poseur->nom ?? 'Inconnu') . ' / ' . ($rdv->client->nom_assure ?? 'Sans client'),
                        'start' => $rdv->start_time,
                        'end' => $rdv->end_time,
                        'color' => $rdv->indisponible_poseur ? '#9CA3AF' : 
                                  ($rdv->ga_gestion ? '#F97316' : '#3B82F6'),
                        'extendedProps' => [
                            'poseur_id' => $rdv->poseur_id,
                            'client_id' => $rdv->client_id,
                            'ga_gestion' => $rdv->ga_gestion,
                            'indisponible_poseur' => $rdv->indisponible_poseur,
                        ],
                    ];
                });

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error fetching events: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'poseur_id' => 'required|exists:poseurs,id',
                'client_id' => 'required|exists:clients,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            // Convert dates to Carbon instances
            $startTime = Carbon::parse($validated['start_time']);
            $endTime = Carbon::parse($validated['end_time']);

            // Conflict detection
            $conflictingRdv = Rdv::where('poseur_id', $validated['poseur_id'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                          ->orWhereBetween('end_time', [$startTime, $endTime])
                          ->orWhere(function ($q) use ($startTime, $endTime) {
                              $q->where('start_time', '<', $startTime)
                                ->where('end_time', '>', $endTime);
                          });
                })
                ->exists();

            if ($conflictingRdv) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le technicien a déjà un rendez-vous à cette heure'
                ], 409);
            }

            DB::beginTransaction();
            
            $rdv = Rdv::create([
                'poseur_id' => $validated['poseur_id'],
                'client_id' => $validated['client_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'indisponible_poseur' => $request->boolean('indisponible_poseur'),
                'ga_gestion' => $request->boolean('ga_gestion'),
                'status' => 'pending'
            ]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $rdv,
                'message' => 'RDV créé avec succès'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation échouée'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating RDV: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rdv = Rdv::findOrFail($id);

            $validated = $request->validate([
                'poseur_id' => 'required|exists:poseurs,id',
                'client_id' => 'required|exists:clients,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            // Convert dates to Carbon instances
            $startTime = Carbon::parse($validated['start_time']);
            $endTime = Carbon::parse($validated['end_time']);

            // Conflict detection (exclude current appointment)
            $conflictingRdv = Rdv::where('poseur_id', $validated['poseur_id'])
                ->where('id', '!=', $id)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                          ->orWhereBetween('end_time', [$startTime, $endTime])
                          ->orWhere(function ($q) use ($startTime, $endTime) {
                              $q->where('start_time', '<', $startTime)
                                ->where('end_time', '>', $endTime);
                          });
                })
                ->exists();

            if ($conflictingRdv) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le technicien a déjà un rendez-vous à cette heure'
                ], 409);
            }

            DB::beginTransaction();
            
            $rdv->update([
                'poseur_id' => $validated['poseur_id'],
                'client_id' => $validated['client_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'indisponible_poseur' => $request->boolean('indisponible_poseur'),
                'ga_gestion' => $request->boolean('ga_gestion')
            ]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $rdv,
                'message' => 'RDV mis à jour avec succès'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'RDV non trouvé'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation échouée'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating RDV: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $rdv = Rdv::findOrFail($id);
            $rdv->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'RDV supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting RDV: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
}