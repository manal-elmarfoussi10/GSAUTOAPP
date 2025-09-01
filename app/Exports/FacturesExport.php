<?php

namespace App\Exports;

use App\Models\Facture;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FacturesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Facture::with('client')->get([
            'numero',
            'date_facture',
            'total_ht',
            'total_tva',
            'total_ttc',
            'is_paid',
            'client_id'
        ]);
    }

    public function headings(): array
    {
        return [
            'Numéro',
            'Date Facture',
            'Total HT',
            'TVA',
            'Total TTC',
            'Statut',
            'Client ID'
        ];
    }
}
