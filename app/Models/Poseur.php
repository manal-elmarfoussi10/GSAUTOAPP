<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CompanyScoped; 

class Poseur extends Model
{
    use CompanyScoped;
    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'mot_de_passe',
        'actif',
        'couleur',
        'rue',
        'code_postal',
        'ville',
        'info',
        'departements',
        'company_id'
    ];

    protected $casts = [
        'departements' => 'array',
        'actif' => 'boolean',
    ];
    public function company() { return $this->belongsTo(Company::class); }
}