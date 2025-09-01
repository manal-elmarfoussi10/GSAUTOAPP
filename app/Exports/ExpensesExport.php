<?php

// app/Exports/ExpensesExport.php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return Expense::with(['client', 'fournisseur'])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Référence Client',
            'Client',
            'Fournisseur',
            'Statut Paiement',
            'Montant HT (€)',
            'Montant TTC (€)',
            'Description'
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->date->format('d/m/Y'),
            $expense->client->reference_client,
            $expense->client->prenom . ' ' . $expense->client->nom_assure,
            $expense->fournisseur->nom_societe,
            $this->getStatusText($expense->paid_status),
            number_format($expense->ht_amount, 2, ',', ' '),
            number_format($expense->ttc_amount, 2, ',', ' '),
            $expense->description
        ];
    }

    private function getStatusText($status)
    {
        return match($status) {
            'paid' => 'Payé',
            'pending' => 'En attente',
            'unpaid' => 'Non payé',
            default => $status
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 
                          'startColor' => ['rgb' => 'FF6B35']]
            ],
            
            // Data rows
            'A:H' => ['alignment' => ['wrapText' => true]],
            
            // Amount columns
            'F:H' => ['alignment' => ['horizontal' => 'right']]
        ];
    }

    public function title(): string
    {
        return 'Dépenses ' . now()->format('d-m-Y');
    }
}
