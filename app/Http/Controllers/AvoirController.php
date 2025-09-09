<?php

namespace App\Http\Controllers;

use App\Models\Avoir;
use App\Models\Facture;
use Illuminate\Http\Request;
use App\Exports\AvoirsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AvoirController extends Controller
{
    /**
     * List all avoirs.
     */
    public function index()
    {
        // We need the facture with its client (may be null for prospect),
        // plus facture->paiements and facture->avoirs for totals.
        $avoirs = Avoir::with([
            'facture.client',
            'facture.paiements',
            'facture.avoirs',
        ])->latest()->get();

        return view('avoirs.index', compact('avoirs'));
    }

    /**
     * Create form (optionally preselected facture via query ?facture_id=).
     */
    public function create(Request $request)
    {
        $facture = null;
        if ($request->filled('facture_id')) {
            $facture = Facture::with(['paiements', 'avoirs', 'client'])
                ->findOrFail($request->facture_id);
        }

        $factures = Facture::with('client')->latest()->get();

        return view('avoirs.create', compact('factures', 'facture'));
    }

    /**
     * Create form when routed like /avoirs/create/{facture}.
     */
    public function createFromFacture(Facture $facture)
    {
        $facture->load(['paiements', 'avoirs', 'client']);
        $factures = Facture::with('client')->latest()->get();

        return view('avoirs.create', compact('factures', 'facture'));
    }

    /**
     * Store a new avoir with hard validation against remaining balance.
     */
    public function store(Request $request)
    {
        $request->validate([
            'facture_id' => ['required', 'exists:factures,id'],
            'montant'    => ['required', 'numeric', 'min:0.01'],
            'notes'      => ['nullable', 'string', 'max:2000'],
        ]);

        $facture = Facture::with(['paiements', 'avoirs'])->findOrFail($request->facture_id);

        $totalPaye  = (float) $facture->paiements->sum('montant');
        $totalAvoir = (float) $facture->avoirs->sum('montant');
        $reste      = round((float) $facture->total_ttc - $totalPaye - $totalAvoir, 2);

        $montant = round((float) $request->montant, 2);
        if ($montant > $reste) {
            return back()
                ->withErrors(['montant' => "Le montant de l’avoir ({$montant} €) dépasse le reste dû ({$reste} €)."])
                ->withInput();
        }

        Avoir::create([
            'facture_id' => $facture->id,
            'montant'    => $montant,
            'notes'      => $request->notes,
        ]);

        return redirect()->route('factures.index')->with('success', 'Avoir enregistré.');
    }

    /**
     * Show one avoir (optional).
     */
    public function show(Avoir $avoir)
    {
        $avoir->load('facture.client');
        return view('avoirs.show', compact('avoir'));
    }

    /**
     * Edit form.
     */
    public function edit(Avoir $avoir)
    {
        $avoir->load('facture.client', 'facture.paiements', 'facture.avoirs');
        $factures = Facture::with('client')->latest()->get();

        return view('avoirs.edit', compact('avoir', 'factures'));
    }

    /**
     * Update an avoir with validation against the up-to-date remaining balance.
     * (Allows keeping the same amount, so we add back this avoir’s current value.)
     */
    public function update(Request $request, Avoir $avoir)
    {
        $request->validate([
            'facture_id' => ['required', 'exists:factures,id'],
            'montant'    => ['required', 'numeric', 'min:0.01'],
            'notes'      => ['nullable', 'string', 'max:2000'],
        ]);

        $facture = Facture::with(['paiements', 'avoirs'])->findOrFail($request->facture_id);

        // Reste disponible si on « retire » d’abord l’avoir que l’on édite
        $totalPaye  = (float) $facture->paiements->sum('montant');
        $totalAvoir = (float) $facture->avoirs->where('id', '!=', $avoir->id)->sum('montant');
        $reste      = round((float) $facture->total_ttc - $totalPaye - $totalAvoir, 2);

        $montant = round((float) $request->montant, 2);
        if ($montant > $reste) {
            return back()
                ->withErrors(['montant' => "Le montant de l’avoir ({$montant} €) dépasse le reste dû ({$reste} €)."])
                ->withInput();
        }

        $avoir->update([
            'facture_id' => $facture->id,
            'montant'    => $montant,
            'notes'      => $request->notes,
        ]);

        return redirect()->route('avoirs.index')->with('success', 'Avoir modifié avec succès.');
    }

    /**
     * Delete an avoir.
     */
    public function destroy(Avoir $avoir)
    {
        $avoir->delete();
        return redirect()->route('avoirs.index')->with('success', 'Avoir supprimé.');
    }

    /**
     * Export list to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new AvoirsExport, 'avoirs.xlsx');
    }

    /**
     * Export list to PDF.
     */
    public function exportPDF()
    {
        $avoirs = Avoir::with('facture.client')->get();
        $pdf = Pdf::loadView('avoirs.pdf', compact('avoirs'));
        return $pdf->download('avoirs.pdf');
    }

    /**
     * Export a single avoir to PDF.
     */
    public function export_PDF(Avoir $avoir)
    {
        $avoir->load('facture.client', 'facture.items');
        $company = auth()->user()->company ?? null;

        $pdf = Pdf::loadView('avoirs.single_pdf', compact('avoir', 'company'));
        return $pdf->download("avoir_{$avoir->id}.pdf");
    }
}