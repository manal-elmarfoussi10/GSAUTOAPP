<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = [
        'nom_societe',
        'email',
        'telephone',
        'categorie',
        'adresse_nom',
        'adresse_rue',
        'adresse_cp',
        'adresse_ville',
        'adresse_facturation',
        'adresse_livraison',
        'adresse_devis',
        'contact_nom',
        'contact_email',
        'contact_telephone'
    ];

    public function expenses()
{
    return $this->hasMany(Expense::class);
}

public function clients()
{
    return $this->hasMany(Client::class);
}
public function bondecommandes()
{
    return $this->hasMany(BonDeCommande::class);
}
}
