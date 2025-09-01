<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\FactureItem;
use Illuminate\Http\Request;
use App\Exports\FacturesExport;
use App\Models\Paiement;
use App\Models\Produit;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

class FactureController extends Controller
{
    public function index()
    {
        $factures = Facture::with('client')->latest()->get();
        return view('factures.index', compact('factures'));
    }

    public function create()
    {
        $clients = Client::all();
        $devis = Devis::all();
        $produits = Produit::all();  // Changed variable name to plural French convention

        return view('factures.create', compact('clients', 'devis', 'produits'));
        //                                Now matches variable ^
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'devis_id' => 'nullable|exists:devis,id',
            'titre' => 'nullable|string|max:255',
            'date_facture' => 'required|date',
            'items.*.produit' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
            'items.*.taux_tva' => 'required|numeric|min:0',
            'items.*.remise' => 'nullable|numeric|min:0|max:100',
        ]);

        // Generate invoice number first
        $today = now()->format('dmy');
        $nextId = Facture::max('id') + 1;
        $numero = $today . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $facture = new Facture();
        $facture->client_id = $request->client_id;
        $facture->devis_id = $request->devis_id;
        $facture->titre = $request->titre;
        $facture->date_facture = $request->date_facture;
        $facture->numero = $numero;

        $totalHT = 0;
        $totalTVA = 0;

        foreach ($request->items as $itemData) {
            $pu = $itemData['prix_unitaire'];
            $qty = $itemData['quantite'];
            $discount = $itemData['remise'] ?? 0;
            $tvaRate = $itemData['taux_tva'] ?? 20;

            $itemTotal = $pu * $qty * (1 - $discount / 100);
            $totalHT += $itemTotal;
            $totalTVA += $itemTotal * ($tvaRate / 100);
        }

        $facture->total_ht = $totalHT;
        $facture->tva = $totalTVA;
        $facture->total_tva = $totalTVA;
        $facture->total_ttc = $totalHT + $totalTVA;
        $facture->save();

        foreach ($request->items as $item) {
            FactureItem::create([
                'facture_id' => $facture->id,
                'produit' => $item['produit'],
                'description' => $item['description'] ?? null,
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix_unitaire'],
                'taux_tva' => $item['taux_tva'] ?? 20,
                'remise' => $item['remise'] ?? 0,
                'total_ht' => $item['prix_unitaire'] * $item['quantite'] * (1 - ($item['remise'] ?? 0) / 100),
            ]);
        }

        return redirect()->route('factures.index')->with('success', 'Facture créée avec succès.');
    }



    public function edit(Facture $facture)
    {
        $clients = Client::all();
        $devis = Devis::all();
        $produits = Produit::all(); // Make sure to include this

        return view('factures.edit', compact('facture', 'clients', 'devis', 'produits'));
    }

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'client_id' => 'nullable|exists:clients,id',
        'date_facture' => 'required|date',
        'items.*.produit' => 'required|string|max:255',
        'items.*.quantite' => 'required|integer|min:1',
        'items.*.prix_unitaire' => 'required|numeric|min:0',
        'items.*.remise' => 'nullable|numeric|min:0|max:100',
    ]);

    $facture = Facture::findOrFail($id);
    $facture->client_id = $request->client_id;
    $facture->date_facture = $request->date_facture;

    $totalHT = 0;
    foreach ($request->items as $itemData) {
        $pu = $itemData['prix_unitaire'];
        $qty = $itemData['quantite'];
        $discount = $itemData['remise'] ?? 0;

        $itemTotal = $pu * $qty * (1 - $discount / 100);
        $totalHT += $itemTotal;
    }

    $facture->total_ht = $totalHT;
    $facture->tva = 20;
    $facture->total_tva = $totalHT * 0.2;
    $facture->total_ttc = $totalHT * 1.2;
    $facture->save();

    // Remove existing items before re-inserting updated ones
    $facture->items()->delete();

    foreach ($request->items as $item) {
        $facture->items()->create([
            'produit' => $item['produit'],
            'quantite' => $item['quantite'],
            'prix_unitaire' => $item['prix_unitaire'],
            'remise' => $item['remise'] ?? 0,
            'total_ht' => $item['prix_unitaire'] * $item['quantite'] * (1 - ($item['remise'] ?? 0) / 100),
        ]);
    }

    return redirect()->route('factures.index')->with('success', 'Facture mise à jour avec succès.');
}

public function exportExcel()
{
    return Excel::download(new FacturesExport, 'factures.xlsx');
}


public function exportFacturesPDF()
{
    try {
        $factures = Facture::with('client')->get();
        $user = auth()->user();

        $company = $user->company ?? (object)[
            'name' => 'Votre Société',
            'address' => 'Adresse non définie',
            'phone' => '',
            'email' => '',
            'logo' => null
        ];

        $logoBase64 = null;
        if ($company->logo) {
            try {
                $logoPath = storage_path('app/public/' . $company->logo);
                if (file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {
                \Log::error('Logo processing error: ' . $e->getMessage());
            }
        }

        $pdf = DomPDF::loadView('factures.export_pdf', [
            'factures' => $factures,
            'company' => $company,
            'logoBase64' => $logoBase64
        ]);

        return $pdf->download('liste_factures_' . now()->format('Ymd_His') . '.pdf');

    } catch (\Exception $e) {
        \Log::error('PDF Export Error: ' . $e->getMessage());
        return back()->with('error', 'Erreur lors de la génération du PDF');
    }
}



public function downloadPdf($id)
{
    $facture = Facture::with(['client', 'items'])->findOrFail($id);

    $user = auth()->user();

    // Données de l'entreprise de l'utilisateur connecté
    $company = $user->company ?? (object)[
        'name' => 'Votre Société',
        'address' => 'Adresse non définie',
        'phone' => '',
        'email' => '',
        'logo' => null
    ];

    // Préparer le logo encodé en base64
    $logoBase64 = null;
    if ($company->logo) {
        $logoPath = storage_path('app/public/' . $company->logo);
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }

    // Génération du PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('factures.pdf', [
        'facture' => $facture,
        'company' => $company,
        'logoBase64' => $logoBase64,
    ]);

    return $pdf->download("facture_{$facture->id}.pdf");
}


public function acquitter($id)
{
    $facture = Facture::with(['paiements', 'avoirs'])->findOrFail($id);

    $totalPaye = $facture->paiements->sum('montant');
    $totalAvoir = $facture->avoirs->sum('montant');
    $reste = $facture->total_ttc - $totalPaye - $totalAvoir;

    if ($reste > 0) {
        Paiement::create([
            'facture_id' => $facture->id,
            'montant' => $reste,
            'mode' => 'Virement', // Default mode
            'date' => now(),
        ]);
    }

    return redirect()->route('factures.index')->with('success', 'Facture acquittée.');
}


}
