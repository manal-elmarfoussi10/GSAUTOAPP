<?php

// app/Models/Devis.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 
        'titre', 
        'date_devis', 
        'date_validite',
        'total_ht',
        'total_tva',
        'total_ttc',
        
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(DevisItem::class);
    }
}


