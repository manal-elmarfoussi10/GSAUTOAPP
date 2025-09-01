<?php

namespace App\Http\Controllers;

use App\Models\BonDeCommande;
use App\Models\BonDeCommandeLigne;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BonsDeCommandeExport;

class BonDeCommandeController extends Controller
{
    public function index(Request $request)
    {
        $bons = BonDeCommande::with(['client', 'fournisseur'])->latest()->get();
        return view('bons-de-commande.index', compact('bons'));
    }

    public function create()
    {
        $clients = Client::orderBy('nom_assure')->get();
        $fournisseurs = Fournisseur::orderBy('nom_societe')->get();
        $produits = Produit::orderBy('nom')->get();
        
        return view('bons-de-commande.create', compact('clients', 'fournisseurs', 'produits'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'titre' => 'nullable|string|max:255',
            'fichier' => 'nullable|file|mimes:pdf,jpg,png,doc,docx,xls,xlsx',
            'date_commande' => 'required|date',
            'total_ht' => 'required|numeric',
            'tva' => 'required|numeric',
            'total_ttc' => 'required|numeric',
        ]);

        if ($request->hasFile('fichier')) {
            $path = $request->file('fichier')->store('bons');
            $data['fichier'] = $path;
        }

        $bon = BonDeCommande::create($data);

        foreach ($request->input('lignes', []) as $ligne) {
            $produitId = $ligne['produit_id'];
        
            if ($produitId === 'autre') {
                $nomProduit = $ligne['nom_produit'] ?? null;
        
                if ($nomProduit) {
                    $produit = Produit::create([
                        'nom' => $nomProduit,
                        'prix' => $ligne['prix'] ?? 0,
                        // You can add more fields like category, reference, etc.
                    ]);
        
                    $produitId = $produit->id;
                } else {
                    continue; // Skip if nom_produit is not defined
                }
            }
        
            $bon->lignes()->create([
                'produit_id' => $produitId,
                'quantite' => $ligne['quantite'],
                'prix_unitaire' => $ligne['prix'] ?? 0,
                'remise' => $ligne['remise'] ?? 0,
                'ajouter_au_stock' => $ligne['ajouter_au_stock'] ?? false,
            ]);
        }

        return redirect()->route('bons-de-commande.index')->with('success', 'Bon de commande créé avec succès.');
    }

    public function edit(BonDeCommande $bon)
    {
        $clients = Client::all();
        $fournisseurs = Fournisseur::all();
        $produits = Produit::all();

        return view('bons-de-commande.edit', compact('bon', 'clients', 'fournisseurs', 'produits'));
    }

    public function update(Request $request, BonDeCommande $bon)
    {
        $data = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'titre' => 'nullable|string|max:255',
            'fichier' => 'nullable|file|mimes:pdf,jpg,png,doc,docx,xls,xlsx',
            'date_commande' => 'required|date',
            'total_ht' => 'required|numeric',
            'tva' => 'required|numeric',
            'total_ttc' => 'required|numeric',
        ]);

        if ($request->hasFile('fichier')) {
            if ($bon->fichier) {
                Storage::delete($bon->fichier);
            }
            $data['fichier'] = $request->file('fichier')->store('bons');
        }

        $bon->update($data);

        $bon->lignes()->delete();

        foreach ($request->input('lignes', []) as $ligne) {
            $produitId = $ligne['produit_id'];

            if ($produitId === 'autre') {
                $nomProduit = $ligne['nom_produit'] ?? null;

                if ($nomProduit) {
                    $produit = Produit::create([
                        'nom' => $nomProduit,
                        'prix' => $ligne['prix'] ?? 0,
                    ]);
                    $produitId = $produit->id;
                } else {
                    continue; // Skip if nom_produit is not defined
                }
            }

            $bon->lignes()->create([
                'produit_id' => $produitId,
                'quantite' => $ligne['quantite'],
                'prix_unitaire' => $ligne['prix'] ?? 0,
                'remise' => $ligne['remise'] ?? 0,
                'ajouter_au_stock' => $ligne['ajouter_au_stock'] ?? false,
            ]);
        }

        return redirect()->route('bons-de-commande.index')->with('success', 'Bon de commande mis à jour.');
    }

    public function destroy(BonDeCommande $bon)
    {
        if ($bon->fichier) {
            Storage::delete($bon->fichier);
        }

        $bon->lignes()->delete();
        $bon->delete();

        return redirect()->route('bons-de-commande.index')->with('success', 'Bon de commande supprimé.');
    }

    public function exportExcel()
    {
        return Excel::download(new BonsDeCommandeExport, 'bons-de-commande.xlsx');
    }

    public function exportPDF()
    {
        $bons = BonDeCommande::with(['client', 'fournisseur', 'lignes.produit'])->get();
        $pdf = Pdf::loadView('bons-de-commande.export_pdf', compact('bons'));
        return $pdf->download('bons-de-commande.pdf');
    }
}