<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Client;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; 

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['client', 'fournisseur'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    // Add other CRUD methods as needed
    public function exportExcel()
    {
        return Excel::download(new ExpensesExport, 'depenses.xlsx');
    }

    public function exportPDF()
    {
        $expenses = Expense::with(['client', 'fournisseur'])
            ->orderBy('date', 'desc')
            ->get();
        
        $totalTtc = $expenses->sum('ttc_amount');
        
        $pdf = Pdf::loadView('expenses.pdf', compact('expenses', 'totalTtc'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('depenses_' . now()->format('Y_m_d') . '.pdf');
    }


    // ExpenseController.php

public function create()
{
    $clients = Client::orderBy('nom_assure')->get();
    $fournisseurs = Fournisseur::orderBy('nom_societe')->get();
    $recentExpenses = Expense::with(['client', 'fournisseur'])
        ->orderBy('date', 'desc')
        ->take(3)
        ->get();

    return view('expenses.create', compact('clients', 'fournisseurs', 'recentExpenses'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'paid_status' => 'required|in:paid,pending,unpaid',
        'client_id' => 'required|exists:clients,id',
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'ht_amount' => 'required|numeric|min:0',
        'ttc_amount' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
    ]);

    // Create the expense
    $expense = Expense::create($validated);
    
    // Handle file uploads
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('expense-attachments');
            // Save to database if needed
        }
    }

    return redirect()->route('expenses.index')
        ->with('success', 'La dépense a été créée avec succès !');
}

// ExpenseController.php

public function edit(Expense $expense)
{
    $clients = Client::orderBy('nom_assure')->get();
    $fournisseurs = Fournisseur::orderBy('nom_societe')->get();
    
    return view('expenses.edit', compact(
        'expense', 
        'clients', 
        'fournisseurs'
    ));
}

public function update(Request $request, Expense $expense)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'paid_status' => 'required|in:paid,pending,unpaid',
        'client_id' => 'required|exists:clients,id',
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'ht_amount' => 'required|numeric|min:0',
        'ttc_amount' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
    ]);

    // Update the expense
    $expense->update($validated);
    
    // Handle file uploads
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('expense-attachments');
            // Save to database if needed
        }
    }

    return redirect()->route('expenses.index')
        ->with('success', 'La dépense a été mise à jour avec succès !');
}
public function destroy($id)
{
    $expense = Expense::findOrFail($id);
    $expense->delete();

    return redirect()->route('expenses.index')
                     ->with('success', 'Dépense supprimée avec succès.');
}


}