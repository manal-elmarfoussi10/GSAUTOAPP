<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactureItem extends Model
{
    protected $fillable = [
        'facture_id',
        'produit',
        'quantite',
        'prix_unitaire',
        'remise',
        'total_ht',
    ];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }
    
}
