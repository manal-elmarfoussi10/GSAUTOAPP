<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = ['facture_id', 'montant', 'mode', 'commentaire', 'date', 'avoir_id'];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function avoir()
{
    return $this->belongsTo(Avoir::class);
}
}

