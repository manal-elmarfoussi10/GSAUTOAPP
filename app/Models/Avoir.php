<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CompanyScoped; 

class Avoir extends Model
{
    use CompanyScoped;
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
