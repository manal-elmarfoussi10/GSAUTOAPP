<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poseur;
use App\Models\Intervention;
use App\Models\Photo;
use App\Models\Commentaire;

class PoseurController extends Controller
{
    public function index()
    {
        $poseurs = Poseur::latest()->paginate(10);
        $totalPoseurs = Poseur::count();
        $activePoseurs = Poseur::where('actif', true)->count();

        return view('poseurs.index', compact('poseurs', 'totalPoseurs', 'activePoseurs'));
    }

    public function create()
    {
        return view('poseurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'required|email|unique:poseurs,email',
            'mot_de_passe' => 'required|string|min:6',
            'actif' => 'boolean',
            'couleur' => 'nullable|string|max:7',
            'rue' => 'nullable|string',
            'code_postal' => 'nullable|string',
            'ville' => 'nullable|string',
            'info' => 'nullable|string',
            'departements' => 'nullable|array',
        ]);

        Poseur::create($validated);

        return redirect()->route('poseurs.index')->with('success', 'Poseur créé avec succès.');
    }

    public function edit(Poseur $poseur)
    {
        return view('poseurs.edit', compact('poseur'));
    }

    public function update(Request $request, Poseur $poseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'required|email|unique:poseurs,email,' . $poseur->id,
            'mot_de_passe' => 'nullable|string|min:6',
            'actif' => 'boolean',
            'couleur' => 'nullable|string|max:7',
            'rue' => 'nullable|string',
            'code_postal' => 'nullable|string',
            'ville' => 'nullable|string',
            'info' => 'nullable|string',
            'departements' => 'nullable|array',
        ]);

        if (!empty($validated['mot_de_passe'])) {
            $poseur->mot_de_passe = $validated['mot_de_passe'];
        }

        $poseur->update($validated);

        return redirect()->route('poseurs.index')->with('success', 'Poseur mis à jour.');
    }

    public function destroy(Poseur $poseur)
    {
        $poseur->delete();
        return redirect()->route('poseurs.index')->with('success', 'Poseur supprimé.');
    }

    public function dashboard()
    {
        if (!auth()->user()->isRole('poseur')) {
            abort(403, 'Accès interdit');
        }

        $interventions = Intervention::with('dossier.client')
            ->where('poseur_id', auth()->id())
            ->get();

        return view('dashboardposeur', compact('interventions'));
    }

    public function commenter(Request $request, $id)
    {
        if (!auth()->user()->isRole('poseur')) {
            abort(403, 'Accès interdit');
        }

        $request->validate([
            'commentaire' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $intervention = Intervention::where('poseur_id', auth()->id())->findOrFail($id);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('interventions', 'public');
            Photo::create([
                'intervention_id' => $intervention->id,
                'url' => $path,
                'commentaire' => $request->commentaire,
            ]);
        } elseif ($request->commentaire) {
            Commentaire::create([
                'intervention_id' => $intervention->id,
                'user_id' => auth()->id(),
                'contenu' => $request->commentaire,
            ]);
        }

        return back()->with('success', 'Ajout effectué');
    }
}
