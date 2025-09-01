<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poseur extends Model
{
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
    ];

    protected $casts = [
        'departements' => 'array',
        'actif' => 'boolean',
    ];
}