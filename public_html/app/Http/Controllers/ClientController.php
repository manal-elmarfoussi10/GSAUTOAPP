<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Email; // Add this for conversations
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ClientController extends Controller
{
    public function create()
    {
        return view('clients.create');
    }

    public function index()
    {
        $clients = Client::with(['factures.avoirs', 'devis'])->latest()->get();

        $columns = [
            'date' => 'Date',
            'dossier' => 'Dossier',
            'statut' => 'Statut GG Auto',
            'assurance' => 'Assurance N° Sinistre',
            'facture' => 'Factures (HT)',
            'avoir' => 'Avoirs (HT)',
            'devis' => 'Devis (HT)',
            'encaisse' => 'Encaissé',
            'cadeau' => 'Cadeau',
            'franchise' => 'Franchise',
            'poseur' => 'Poseur',
            'vitrage' => 'Vitrage',
            'phone' => 'Phone',
            'marge' => 'Marge',
        ];

        return view('clients.index', compact('clients', 'columns'));
    }

    // app/Http/Controllers/ClientController.php
// app/Http/Controllers/ClientController.php
// app/Http/Controllers/ClientController.php
    public function show(Client $client)
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $allowedRoles = [
            User::ROLE_ADMIN,
            User::ROLE_CLIENT_SERVICE,
            User::ROLE_CLIENT_LIMITED,
            User::ROLE_PLANNER,
            User::ROLE_SUPERADMIN,
        ];

        $client->load([
            'factures.avoirs',
            'devis',
            'photos',
            'conversations' => function ($q) {
                $q->with([
                    'creator',
                    'emails' => function ($query) {
                        $query->with(['senderUser', 'receiverUser', 'replies.senderUser']);
                    },
                ])->orderBy('created_at', 'desc');
            },
        ]);

        $users = User::where('company_id', $user->company_id)
            ->whereIn('role', $allowedRoles)
            ->get();

        return view('clients.show', compact('client', 'users'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        // Validate the request data
        $validated = $request->validate([
            // Add your validation rules here
        ]);

        $data = $request->all();

        // Handle image removal and updates
        $this->handleImageUpdates($request, $client, $data);

        $client->update($data);

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Dossier mis à jour avec succès');
    }

    protected function handleImageUpdates(Request $request, Client $client, array &$data)
    {
        $imageFields = [
            'photo_vitrage',
            'photo_carte_verte',
            'photo_carte_grise'
        ];

        foreach ($imageFields as $field) {
            $remove = "remove_" . $field;

            // Si checkbox de suppression cochée
            if ($request->has($remove)) {
                if ($client->$field) {
                    Storage::disk('public')->delete($client->$field);
                }
                $data[$field] = null;
            }

            // Si nouveau fichier uploadé
            if ($request->hasFile($field)) {
                if ($client->$field) {
                    Storage::disk('public')->delete($client->$field);
                }

                $path = $request->file($field)->store('clients', 'public');
                $data[$field] = $path;
            }
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_assure' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'ville' => 'nullable|string|max:100',
            'plaque' => 'nullable|string|max:20',
            'nom_assurance' => 'nullable|string|max:255',
            'autre_assurance' => 'nullable|string|max:255',
            'numero_police' => 'nullable|string|max:100',
            'date_sinistre' => 'nullable|date',
            'date_declaration' => 'nullable|date',
            'raison' => 'nullable|string|max:255',
            'type_vitrage' => 'nullable|string|max:255',
            'professionnel' => 'nullable|string|max:255',
            'photo_vitrage' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'photo_carte_verte' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'photo_carte_grise' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'type_cadeau' => 'nullable|string|max:255',
            'numero_sinistre' => 'nullable|string|max:255',
            'kilometrage' => 'nullable|string|max:255',
            'connu_par' => 'nullable|string|max:255',
            'adresse_pose' => 'nullable|string|max:255',
            'reference_interne' => 'nullable|string|max:255',
            'reference_client' => 'nullable|string|max:255',
            'precision' => 'nullable|string',
        ]);

        $validated['ancien_modele_plaque'] = $request->has('ancien_modele_plaque');
        $validated['reparation'] = $request->has('reparation');

        foreach (['photo_vitrage', 'photo_carte_verte', 'photo_carte_grise'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('uploads', 'public');
            }
        }

        Client::create($validated);

        return redirect()->route('clients.create')->with('success', 'Dossier client créé avec succès.');
    }

    public function storePhoto(Request $request, Client $client)
    {
        $path = $request->file('photo')->store('photos', 'public');

        $client->photos()->create([
            'type' => $request->input('type'),
            'path' => $path,
        ]);

        return back()->with('success', 'Photo ajoutée.');
    }

    public function updateStatutInterne(Request $request, Client $client)
    {
        $request->validate([
            'statut_interne' => 'nullable|string|max:255',
        ]);

        $client->statut_interne = $request->statut_interne;
        $client->save();

        return redirect()->back()->with('success', 'Statut interne mis à jour.');
    }

    public function rdvs()
    {
        return $this->hasMany(\App\Models\Rdv::class);
    }

    public function destroy(Client $client)
    {
        try {
            // Supprimer les conversations (et emails associés si nécessaire)
            foreach ($client->conversations as $conversation) {
                $conversation->emails()->delete(); // si emails a besoin d'être supprimé
                $conversation->delete();
            }

            // Supprimer les devis et factures
            $client->factures()->delete();
            $client->devis()->delete();

            // Supprimer le client
            $client->delete();

            return redirect()->route('clients.index')
                ->with('success', 'Client supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('clients.index')
                ->with('error', 'Erreur lors de la suppression du client : ' . $e->getMessage());
        }
    }

    public function exportPdf(Client $client)
    {
        $client->load(['factures', 'avoirs', 'devis', 'photos']);

        $pdf = Pdf::loadView('clients.pdf', compact('client'));

        $filename = 'client_' . $client->id . '_'. now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
