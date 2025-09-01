<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Poseur;
use App\Models\Produit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StocksExport;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['client', 'fournisseur', 'poseur', 'produit']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Add pagination
        $stocks = $query->orderByDesc('date')->paginate(10);

        return view('stocks.index', compact('stocks'));
    }

    public function create()
    {
        $clients = Client::all();
        $fournisseurs = Fournisseur::all();
        $poseurs = Poseur::all();
        $produits = Produit::all();
        $statuts = ['À COMMANDER', 'COMMANDÉ', 'LIVRÉ', 'INSTALLÉ'];

        return view('stocks.create', compact('clients', 'fournisseurs', 'poseurs', 'produits', 'statuts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'nullable|date',
            'client_id' => 'nullable|exists:clients,id',
            'libelle_dossier' => 'nullable|string|max:255',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'poseur_id' => 'nullable|exists:poseurs,id',
            'produit_id' => 'required|exists:produits,id',
            'reference' => 'nullable|string|max:255',
            'statut' => 'required|string|max:50',
            'accord' => 'nullable|boolean',
        ]);

        $data['accord'] = $request->has('accord');

        Stock::create($data);

        return redirect()->route('stocks.index')->with('success', 'Produit stocké avec succès.');
    }

    public function edit(Stock $stock)
    {
        $clients = Client::all();
        $fournisseurs = Fournisseur::all();
        $poseurs = Poseur::all();
        $produits = Produit::all();
        $statuts = ['À COMMANDER', 'COMMANDÉ', 'LIVRÉ', 'INSTALLÉ'];

        return view('stocks.edit', compact('stock', 'clients', 'fournisseurs', 'poseurs', 'produits', 'statuts'));
    }

    public function update(Request $request, Stock $stock)
    {
        $data = $request->validate([
            'date' => 'nullable|date',
            'client_id' => 'nullable|exists:clients,id',
            'libelle_dossier' => 'nullable|string|max:255',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'poseur_id' => 'nullable|exists:poseurs,id',
            'produit_id' => 'required|exists:produits,id',
            'reference' => 'nullable|string|max:255',
            'statut' => 'required|string|max:50',
            'accord' => 'nullable|boolean',
        ]);

        $data['accord'] = $request->has('accord');

        $stock->update($data);

        return redirect()->route('stocks.index')->with('success', 'Stock modifié avec succès.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('stocks.index')->with('success', 'Stock supprimé avec succès.');
    }

    public function exportExcel()
    {
        return Excel::download(new StocksExport, 'stocks.xlsx');
    }

    public function exportPDF()
    {
        $stocks = Stock::with(['fournisseur', 'produit', 'poseur'])->get();
        $pdf = Pdf::loadView('stocks.export_pdf', compact('stocks'));
        return $pdf->download('stocks.pdf');
    }
}