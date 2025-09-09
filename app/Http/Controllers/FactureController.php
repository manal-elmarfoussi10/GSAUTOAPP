<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\FactureItem;
use App\Models\Paiement;
use App\Models\Produit;
use App\Exports\FacturesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

class FactureController extends Controller
{
    public function index()
    {
        // Eager-load client and devis (for prospect_name fallback)
        $factures = Facture::with([
            'client:id,nom_assure',
            'devis:id,prospect_name'
        ])->latest()->get();

        return view('factures.index', compact('factures'));
    }

    public function create()
    {
        $clients  = Client::all();
        $devis    = Devis::all();
        $produits = Produit::all();

        return view('factures.create', compact('clients', 'devis', 'produits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'               => 'nullable|exists:clients,id',
            'devis_id'                => 'nullable|exists:devis,id',
            'titre'                   => 'nullable|string|max:255',
            'date_facture'            => 'required|date',
            'items'                   => 'required|array|min:1',
            'items.*.produit'         => 'required|string|max:255',
            'items.*.description'     => 'nullable|string',
            'items.*.quantite'        => 'required|numeric|min:1',
            'items.*.prix_unitaire'   => 'required|numeric|min:0',
            'items.*.taux_tva'        => 'required|numeric|min:0',
            'items.*.remise'          => 'nullable|numeric|min:0|max:100',
        ]);

        // Numéro unique (ex: 020925 + 0001)
        $today  = now()->format('dmy');
        $nextId = (int) (Facture::max('id') ?? 0) + 1;
        $numero = $today . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $facture               = new Facture();
        $facture->client_id    = $request->client_id;
        $facture->devis_id     = $request->devis_id;
        $facture->titre        = $request->titre;
        $facture->date_facture = $request->date_facture;
        $facture->numero       = $numero;

        $totalHT  = 0.0;
        $totalTVA = 0.0;

        foreach ($request->items as $item) {
            $pu       = (float) $item['prix_unitaire'];
            $qty      = (float) $item['quantite'];
            $discount = (float) ($item['remise'] ?? 0);
            $tvaRate  = (float) ($item['taux_tva'] ?? 20);

            $itemHT  = round($pu * $qty * (1 - $discount / 100), 2);
            $itemTVA = round($itemHT * ($tvaRate / 100), 2);

            $totalHT  += $itemHT;
            $totalTVA += $itemTVA;
        }

        $totalHT  = round($totalHT, 2);
        $totalTVA = round($totalTVA, 2);
        $totalTTC = round($totalHT + $totalTVA, 2);

        $facture->total_ht  = $totalHT;
        $facture->tva       = 20;
        $facture->total_tva = $totalTVA;
        $facture->total_ttc = $totalTTC;
        $facture->save();

        foreach ($request->items as $item) {
            $pu       = (float) $item['prix_unitaire'];
            $qty      = (float) $item['quantite'];
            $discount = (float) ($item['remise'] ?? 0);

            $lineHT = round($pu * $qty * (1 - $discount / 100), 2);

            FactureItem::create([
                'facture_id'    => $facture->id,
                'produit'       => $item['produit'],
                'description'   => $item['description'] ?? null,
                'quantite'      => $qty,
                'prix_unitaire' => round($pu, 2),
                'taux_tva'      => (float) ($item['taux_tva'] ?? 20),
                'remise'        => $discount,
                'total_ht'      => $lineHT,
            ]);
        }

        return redirect()->route('factures.index')->with('success', 'Facture créée avec succès.');
    }

    public function edit(Facture $facture)
    {
        $clients  = Client::all();
        $devis    = Devis::all();
        $produits = Produit::all();

        return view('factures.edit', compact('facture', 'clients', 'devis', 'produits'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'client_id'               => 'nullable|exists:clients,id',
            'date_facture'            => 'required|date',
            'items'                   => 'required|array|min:1',
            'items.*.produit'         => 'required|string|max:255',
            'items.*.quantite'        => 'required|numeric|min:1',
            'items.*.prix_unitaire'   => 'required|numeric|min:0',
            'items.*.taux_tva'        => 'required|numeric|min:0',
            'items.*.remise'          => 'nullable|numeric|min:0|max:100',
        ]);

        $facture = Facture::findOrFail($id);
        $facture->client_id    = $request->client_id;
        $facture->date_facture = $request->date_facture;

        $totalHT  = 0.0;
        $totalTVA = 0.0;

        foreach ($request->items as $item) {
            $pu       = (float) $item['prix_unitaire'];
            $qty      = (float) $item['quantite'];
            $discount = (float) ($item['remise'] ?? 0);
            $tvaRate  = (float) ($item['taux_tva'] ?? 20);

            $itemHT  = round($pu * $qty * (1 - $discount / 100), 2);
            $itemTVA = round($itemHT * ($tvaRate / 100), 2);

            $totalHT  += $itemHT;
            $totalTVA += $itemTVA;
        }

        $totalHT  = round($totalHT, 2);
        $totalTVA = round($totalTVA, 2);
        $totalTTC = round($totalHT + $totalTVA, 2);

        $facture->total_ht  = $totalHT;
        $facture->tva       = 20;
        $facture->total_tva = $totalTVA;
        $facture->total_ttc = $totalTTC;
        $facture->save();

        // Replace items
        $facture->items()->delete();

        foreach ($request->items as $item) {
            $pu       = (float) $item['prix_unitaire'];
            $qty      = (float) $item['quantite'];
            $discount = (float) ($item['remise'] ?? 0);

            $lineHT = round($pu * $qty * (1 - $discount / 100), 2);

            $facture->items()->create([
                'produit'       => $item['produit'],
                'description'   => $item['description'] ?? null,
                'quantite'      => $qty,
                'prix_unitaire' => round($pu, 2),
                'taux_tva'      => (float) ($item['taux_tva'] ?? 20),
                'remise'        => $discount,
                'total_ht'      => $lineHT,
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
            // Include devis for prospect_name in the PDF list
            $factures = Facture::with([
                'client:id,nom_assure',
                'devis:id,prospect_name'
            ])->get();

            $user     = auth()->user();

            $company = $user->company ?? (object) [
                'name'    => 'Votre Société',
                'address' => 'Adresse non définie',
                'phone'   => '',
                'email'   => '',
                'logo'    => null,
            ];

            $logoBase64 = null;
            if ($company->logo) {
                try {
                    $logoPath = storage_path('app/public/' . $company->logo);
                    if (file_exists($logoPath)) {
                        $type       = pathinfo($logoPath, PATHINFO_EXTENSION);
                        $data       = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Logo processing error: '.$e->getMessage());
                }
            }

            $pdf = DomPDF::loadView('factures.export_pdf', [
                'factures'   => $factures,
                'company'    => $company,
                'logoBase64' => $logoBase64,
            ]);

            return $pdf->download('liste_factures_' . now()->format('Ymd_His') . '.pdf');

        } catch (\Throwable $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la génération du PDF');
        }
    }

    public function downloadPdf($id)
    {
        // Also load devis if your facture template wants to show prospect_name
        $facture = Facture::with(['client', 'items', 'devis:id,prospect_name'])->findOrFail($id);
        $user    = auth()->user();

        $company = $user->company ?? (object) [
            'name'    => 'Votre Société',
            'address' => 'Adresse non définie',
            'phone'   => '',
            'email'   => '',
            'logo'    => null,
        ];

        $logoBase64 = null;
        if ($company->logo) {
            $logoPath = storage_path('app/public/' . $company->logo);
            if (file_exists($logoPath)) {
                $type       = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data       = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $pdf = DomPDF::loadView('factures.pdf', [
            'facture'    => $facture,
            'company'    => $company,
            'logoBase64' => $logoBase64,
        ]);

        return $pdf->download("facture_{$facture->id}.pdf");
    }

    public function acquitter($id)
    {
        $facture = Facture::with(['paiements', 'avoirs'])->findOrFail($id);

        $totalPaye  = (float) $facture->paiements->sum('montant');
        $totalAvoir = (float) $facture->avoirs->sum('montant');
        $reste      = round((float) $facture->total_ttc - $totalPaye - $totalAvoir, 2);

        if ($reste > 0) {
            Paiement::create([
                'facture_id' => $facture->id,
                'montant'    => $reste,
                'mode'       => 'Virement',
                'date'       => now(),
            ]);
        }

        return redirect()->route('factures.index')->with('success', 'Facture acquittée.');
    }
}