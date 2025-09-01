<?php

namespace App\Http\Controllers;

use App\Models\Avoir;
use App\Models\Facture;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Exports\AvoirsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AvoirController extends Controller
{
    // Display list of avoirs
    public function index()
    {
        $avoirs = Avoir::with([
            'facture.client.rdvs',
            'paiements'  // Make sure this relationship exists
        ])->latest()->get();
        
        return view('avoirs.index', compact('avoirs'));
    }

    // Show the create form
    public function create()
    {
        $factures = Facture::with('client')->get(); // useful for dropdown
        return view('avoirs.create', compact('factures'));
    }

    // Store a new avoir
    public function store(Request $request)
    {
        $request->validate([
            'facture_id' => 'required|exists:factures,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        Avoir::create([
            'facture_id' => $request->facture_id,
            'montant' => $request->montant,
        ]);

        return redirect()->route('avoirs.index')->with('success', 'Avoir créé avec succès.');
    }

    // Optional: Show a single avoir
    public function show(Avoir $avoir)
    {
        return view('avoirs.show', compact('avoir'));
    }

    // Optional: Edit form
    public function edit(Avoir $avoir)
    {
        $factures = Facture::with('client')->get();
        return view('avoirs.edit', compact('avoir', 'factures'));
    }

    // Optional: Update an avoir
    public function update(Request $request, Avoir $avoir)
    {
        $request->validate([
            'facture_id' => 'required|exists:factures,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        $avoir->update([
            'facture_id' => $request->facture_id,
            'montant' => $request->montant,
        ]);

        return redirect()->route('avoirs.index')->with('success', 'Avoir modifié avec succès.');
    }

    // Optional: Delete
    public function destroy(Avoir $avoir)
    {
        $avoir->delete();
        return redirect()->route('avoirs.index')->with('success', 'Avoir supprimé.');
    }

    public function exportExcel()
{
    return Excel::download(new AvoirsExport, 'avoirs.xlsx');
}

public function exportPDF()
{
    $avoirs = Avoir::with('facture.client')->get();

    $pdf = Pdf::loadView('avoirs.pdf', compact('avoirs'));
    return $pdf->download('avoirs.pdf');
}
public function createFromFacture(Facture $facture)
{
    $factures = Facture::with('client')->get();
return view('avoirs.create', compact('facture', 'factures'));
}

public function export_PDF(Avoir $avoir)
{
    $avoir->load('facture.client', 'facture.items');
    $company = auth()->user()->company; // ou autre logique selon ton projet

    $pdf = \PDF::loadView('avoirs.single_pdf', compact('avoir', 'company'));
    return $pdf->download("avoir_{$avoir->id}.pdf");
}
}