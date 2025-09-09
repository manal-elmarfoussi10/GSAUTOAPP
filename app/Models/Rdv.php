<?php

// app/Models/Rdv.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CompanyScoped; 

class Rdv extends Model
{
    use CompanyScoped;
    protected $fillable = [
        'poseur_id',
        'client_id',
        'start_time',
        'end_time',
        'indisponible_poseur',
        'ga_gestion',
        'status',
        'company_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'indisponible_poseur' => 'boolean',
        'ga_gestion' => 'boolean',
    ];

    public function poseur()
    {
        return $this->belongsTo(Poseur::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function company() { return $this->belongsTo(Company::class); }

}