<?php

namespace App\Exports;

use App\Models\Avoir;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AvoirsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Avoir::with('facture.client')->get()->map(function ($avoir) {
            return [
                'ID' => $avoir->id,
                'Date' => $avoir->created_at->format('d/m/Y'),
                'Client' => optional($avoir->facture->client)->nom_assure,
                'Montant' => $avoir->montant,
                'Facture ID' => $avoir->facture_id,
                'Année fiscale' => optional($avoir->created_at)->format('Y'),
                'Date RDV' => optional(optional($avoir->facture->client)->rdvs->first())->start_time ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Date', 'Client', 'Montant', 'Facture ID', 'Année fiscale', 'Date RDV'];
    }
}
