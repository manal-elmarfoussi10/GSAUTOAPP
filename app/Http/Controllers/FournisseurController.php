<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::latest()->get();
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom_societe' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string',
            'categorie' => 'nullable|string',

            'adresse_nom' => 'nullable|string',
            'adresse_rue' => 'nullable|string',
            'adresse_cp' => 'nullable|string',
            'adresse_ville' => 'nullable|string',

            'adresse_facturation' => 'nullable',
            'adresse_livraison'   => 'nullable',
            'adresse_devis'       => 'nullable',

            'contact_nom' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_telephone' => 'nullable|string',
        ]);

        // Force boolean checkboxes to 0/1
        $data['adresse_facturation'] = $request->has('adresse_facturation');
        $data['adresse_livraison'] = $request->has('adresse_livraison');
        $data['adresse_devis'] = $request->has('adresse_devis');

        Fournisseur::create($data);

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur ajouté avec succès.');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $data = $request->validate([
            'nom_societe' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string',
            'categorie' => 'nullable|string',

            'adresse_nom' => 'nullable|string',
            'adresse_rue' => 'nullable|string',
            'adresse_cp' => 'nullable|string',
            'adresse_ville' => 'nullable|string',

            'adresse_facturation' => 'nullable',
            'adresse_livraison'   => 'nullable',
            'adresse_devis'       => 'nullable',

            'contact_nom' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_telephone' => 'nullable|string',
        ]);

        $data['adresse_facturation'] = $request->has('adresse_facturation');
        $data['adresse_livraison'] = $request->has('adresse_livraison');
        $data['adresse_devis'] = $request->has('adresse_devis');

        $fournisseur->update($data);

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur modifié avec succès.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}