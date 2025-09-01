<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonDeCommandeLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'produit_nom',
        'quantite',
        'prix_unitaire',
        'remise',
        'ajouter_au_stock',
    ];

    public function bon()
    {
        return $this->belongsTo(BonDeCommande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}