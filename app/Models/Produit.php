<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'nom', 'code', 'description', 'prix_ht', 'montant_tva', 'categorie', 'actif',
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }
}
