<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'date',
        'client_id',
        'fournisseur_id',
        'paid_status',
        'ht_amount',
        'ttc_amount',
        'description' // Add this
    ];
    
    // Add this casting
    protected $casts = [
        'date' => 'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}
