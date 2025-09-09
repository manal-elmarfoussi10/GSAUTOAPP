<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CompanyScoped; 

class Paiement extends Model
{
    use CompanyScoped;
    protected $fillable = ['facture_id', 'montant', 'mode', 'commentaire', 'date', 'avoir_id', 'company_id'];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function avoir()
{
    return $this->belongsTo(Avoir::class);
}
public function company() { return $this->belongsTo(Company::class); }
}
