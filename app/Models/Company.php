<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'commercial_name',
        'email',
        'phone',
        'siret',
        'tva',
        'iban',
        'bic',
        'ape',
        'address',
        'postal_code',
        'city',
        'logo',
        'rib',
        'kbis',
        'id_photo_recto',
        'id_photo_verso',
        'tva_exemption_doc',
        'invoice_terms_doc',
        'known_by',
        'contact_permission',
        'garage_type',
        'legal_form',
        'capital',
        'head_office_address',
        'rcs_number',
        'rcs_city',
        'naf_code',
        'professional_insurance',
        'representative',
        'tva_regime',
        'eco_contribution',
        'penalty_rate',
        'methode_paiement',
    ];

    public function users()
{
    return $this->hasMany(User::class);
}


}