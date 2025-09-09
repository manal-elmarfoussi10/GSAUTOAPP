<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Client;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * List expenses (scoped to the current company).
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;

        $expenses = Expense::with(['client', 'fournisseur'])
            ->where('company_id', $companyId)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Export to Excel (scoped).
     */
    public function exportExcel()
    {
        // Your export class should itself apply company scoping,
        // or you can pass the company id into its constructor.
        return Excel::download(new ExpensesExport, 'depenses.xlsx');
    }

    /**
     * Export to PDF (scoped).
     */
    public function exportPDF()
    {
        $companyId = auth()->user()->company_id;

        $expenses = Expense::with(['client', 'fournisseur'])
            ->where('company_id', $companyId)
            ->orderBy('date', 'desc')
            ->get();

        $totalTtc = $expenses->sum('ttc_amount');

        $pdf = Pdf::loadView('expenses.pdf', compact('expenses', 'totalTtc'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('depenses_' . now()->format('Y_m_d') . '.pdf');
    }

    /**
     * Show create form (scoped).
     */
    public function create()
    {
        $companyId = auth()->user()->company_id;

        $clients = Client::where('company_id', $companyId)
            ->orderBy('nom_assure')
            ->get();

        $fournisseurs = Fournisseur::where('company_id', $companyId)
            ->orderBy('nom_societe')
            ->get();

        $recentExpenses = Expense::with(['client', 'fournisseur'])
            ->where('company_id', $companyId)
            ->orderBy('date', 'desc')
            ->take(3)
            ->get();

        return view('expenses.create', compact('clients', 'fournisseurs', 'recentExpenses'));
    }

    /**
     * Store a new expense (scoped).
     */
    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $validated = $request->validate([
            'date'            => 'required|date',
            'paid_status'     => 'required|in:paid,pending,unpaid',
            'client_id'       => 'required|exists:clients,id',
            'fournisseur_id'  => 'required|exists:fournisseurs,id',
            'ht_amount'       => 'required|numeric|min:0',
            'ttc_amount'      => 'required|numeric|min:0',
            'description'     => 'nullable|string',
            'attachments.*'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Attach company id
        $data = $validated + ['company_id' => $companyId];

        // Persist expense
        $expense = Expense::create($data);

        // Optional: store attachments (paths). Adjust to your schema if you store them in a table/JSON column.
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("public/companies/{$companyId}/expenses");
                // TODO: Save $path to a related attachments table or a JSON column on $expense if you have one.
            }
        }

        return redirect()
            ->route('expenses.index')
            ->with('success', 'La dépense a été créée avec succès !');
    }

    /**
     * Edit form (scoped).
     */
    public function edit(Expense $expense)
    {
        $companyId = auth()->user()->company_id;

        // Guard: prevent cross-company access if route-model binding didn’t scope automatically.
        abort_unless($expense->company_id === $companyId, 403);

        $clients = Client::where('company_id', $companyId)
            ->orderBy('nom_assure')
            ->get();

        $fournisseurs = Fournisseur::where('company_id', $companyId)
            ->orderBy('nom_societe')
            ->get();

        return view('expenses.edit', compact('expense', 'clients', 'fournisseurs'));
    }

    /**
     * Update (scoped).
     */
    public function update(Request $request, Expense $expense)
    {
        $companyId = auth()->user()->company_id;
        abort_unless($expense->company_id === $companyId, 403);

        $validated = $request->validate([
            'date'            => 'required|date',
            'paid_status'     => 'required|in:paid,pending,unpaid',
            'client_id'       => 'required|exists:clients,id',
            'fournisseur_id'  => 'required|exists:fournisseurs,id',
            'ht_amount'       => 'required|numeric|min:0',
            'ttc_amount'      => 'required|numeric|min:0',
            'description'     => 'nullable|string',
            'attachments.*'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Always enforce company_id
        $expense->update($validated + ['company_id' => $companyId]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("public/companies/{$companyId}/expenses");
                // TODO: Save $path somewhere if needed.
            }
        }

        return redirect()
            ->route('expenses.index')
            ->with('success', 'La dépense a été mise à jour avec succès !');
    }

    /**
     * Destroy (scoped).
     */
    public function destroy(Expense $expense)
    {
        $companyId = auth()->user()->company_id;
        abort_unless($expense->company_id === $companyId, 403);

        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Dépense supprimée avec succès.');
    }
}