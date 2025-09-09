<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CompanyScoped; 

class Facture extends Model
{
    use CompanyScoped;
    protected $fillable = [
        'client_id',
        'devis_id',
        'titre',
        'date_facture',
        'total_ht',
        'tva',
        'total_tva',
        'total_ttc',
        'is_paid',
        'date_paiement',
        'methode_paiement',
        'company_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function items()
    {
        return $this->hasMany(FactureItem::class);
    }
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
    public function avoirs()
    {
        return $this->hasMany(Avoir::class);
    }
    public function company() { return $this->belongsTo(Company::class); }

    public function getDisplayNameAttribute(): string
{
    return $this->client->nom_assure
        ?? ($this->devis->prospect_name ?? '-');
}
}
