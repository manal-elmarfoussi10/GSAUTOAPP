<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CompanyScoped; 

class Stock extends Model
{
    use CompanyScoped;
    protected $fillable = [
        'date', 'client_id', 'libelle_dossier',
        'fournisseur_id', 'poseur_id', 'produit_id',
        'reference', 'statut', 'accord', 'company_id',
    ];

    protected $casts = [
        'date' => 'date',
        'accord' => 'boolean',
    ];

    public function client()       { return $this->belongsTo(Client::class); }
    public function fournisseur()  { return $this->belongsTo(Fournisseur::class); }
    public function poseur()       { return $this->belongsTo(Poseur::class); }
    public function produit()      { return $this->belongsTo(Produit::class); }
    public function company() { return $this->belongsTo(Company::class); }
}
