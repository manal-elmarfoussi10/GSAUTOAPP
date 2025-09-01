<?php

// app/Models/DevisItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevisItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'devis_id',
        'produit',
        'description',
        'quantite',
        'prix_unitaire',
        'taux_tva',
        'remise',
        'total_ht'
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }
}
