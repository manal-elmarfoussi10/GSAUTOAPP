<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StocksExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Stock::with(['fournisseur', 'produit', 'poseur'])->get()->map(function ($stock) {
            return [
                'Date' => $stock->date,
                'Produit' => $stock->produit->nom ?? '',
                'Fournisseur' => $stock->fournisseur->nom_societe ?? '',
                'Statut' => $stock->statut,
                'Poseur' => $stock->poseur->nom ?? '',
                'Référence' => $stock->reference,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Produit', 'Fournisseur', 'Statut', 'Poseur', 'Référence'];
    }
}
