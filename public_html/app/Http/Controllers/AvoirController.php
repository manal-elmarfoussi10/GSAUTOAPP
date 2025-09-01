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
    public function index()
    {
        $avoirs = Avoir::with([
            'facture.client.rdvs',
            'paiements'
        ])->latest()->get();

        return view('avoirs.index', compact('avoirs'));
    }

    public function create()
    {
        $factures = Facture::with('client')->get();
        return view('avoirs.create', compact('factures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facture_id' => 'required|exists:factures,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        Avoir::create($request->only(['facture_id', 'montant']));

        return redirect()->route('avoirs.index')->with('success', 'Avoir créé avec succès.');
    }

    public function show(Avoir $avoir)
    {
        return view('avoirs.show', compact('avoir'));
    }

    public function edit(Avoir $avoir)
    {
        $factures = Facture::with('client')->get();
        return view('avoirs.edit', compact('avoir', 'factures'));
    }

    public function update(Request $request, Avoir $avoir)
    {
        $request->validate([
            'facture_id' => 'required|exists:factures,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        $avoir->update($request->only(['facture_id', 'montant']));

        return redirect()->route('avoirs.index')->with('success', 'Avoir modifié avec succès.');
    }

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
    return Pdf::loadHTML('<h1>Hello Test PDF</h1>')
        ->setPaper('a4')
        ->download('test.pdf');
}

    public function createFromFacture(Facture $facture)
    {
        $factures = Facture::with('client')->get();
        return view('avoirs.create', compact('facture', 'factures'));
    }

    public function export_PDF(Avoir $avoir)
    {
        $avoir->load('facture.client', 'facture.items');
        $company = auth()->user()->company;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('avoirs.single_pdf', compact('avoir', 'company'));
        return $pdf->download("avoir_{$avoir->id}.pdf");
    }
}