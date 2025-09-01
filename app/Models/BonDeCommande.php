<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonDeCommande extends Model
{
    use HasFactory;

    protected $table = 'bons_de_commande';

    protected $fillable = [
        'client_id',
        'fournisseur_id',
        'titre',
        'fichier',
        'date_commande',
        'total_ht',
        'tva',
        'total_ttc',
    ];

    // 🔗 Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function lignes()
    {
        return $this->hasMany(BonDeCommandeLigne::class);
    }
}