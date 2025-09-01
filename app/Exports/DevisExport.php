<?php

namespace App\Exports;

use App\Models\Devis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DevisExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Devis::with('client')->get()->map(function ($devis) {
            return [
                'Date de devis' => $devis->date_devis,
                'Nom Client' => $devis->client->nom_assure ?? '-',
                'Montant HT' => $devis->total_ht,
                'Montant TTC' => $devis->total_ttc,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date de devis',
            'Nom Client',
            'Montant HT',
            'Montant TTC',
        ];
    }
}
