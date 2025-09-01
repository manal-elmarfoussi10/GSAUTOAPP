<?php

namespace App\Http\Controllers;

use App\Models\BonDeCommandeLigne;
use Illuminate\Http\Request;

class BonDeCommandeLigneController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bon_de_commande_id' => 'required|exists:bons_de_commande,id',
            'produit_id' => 'nullable|exists:produits,id',
            'nom_produit' => 'nullable|string|max:255',
            'quantite' => 'required|numeric|min:1',
            'prix_ht' => 'required|numeric|min:0',
            'remise' => 'nullable|numeric|min:0',
            'total_ht' => 'required|numeric|min:0',
            'ajouter_stock' => 'nullable|boolean',
        ]);

        BonDeCommandeLigne::create($request->all());

        return back()->with('success', 'Ligne ajoutée.');
    }

    public function destroy(BonDeCommandeLigne $ligne)
    {
        $ligne->delete();
        return back()->with('success', 'Ligne supprimée.');
    }
}