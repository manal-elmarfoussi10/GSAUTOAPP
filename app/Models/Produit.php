<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CompanyScoped; 

class Produit extends Model
{
    use CompanyScoped;
    protected $fillable = [
        'nom', 'code', 'description', 'prix_ht', 'montant_tva', 'categorie', 'actif', 'company_id'
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }
    public function company() { return $this->belongsTo(Company::class); }
}
