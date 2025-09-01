<?php

namespace App\Exports;

use App\Models\BonDeCommande;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BonsDeCommandeExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BonDeCommande::with(['client', 'fournisseur'])->get()->map(function ($bon) {
            return [
                'ID' => $bon->id,
                'Date' => $bon->date_commande,
                'Client' => optional($bon->client)->nom,
                'Fournisseur' => optional($bon->fournisseur)->nom_societe,
                'Titre' => $bon->titre,
                'Total HT' => $bon->total_ht,
                'TVA' => $bon->tva,
                'Total TTC' => $bon->total_ttc,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Client',
            'Fournisseur',
            'Titre',
            'Total HT',
            'TVA',
            'Total TTC',
        ];
    }
}
