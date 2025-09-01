<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avoir extends Model
{
    protected $fillable = ['facture_id', 'montant'];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }
  
    
public function paiements()
{
    return $this->hasMany(Paiement::class);
}

    
}
