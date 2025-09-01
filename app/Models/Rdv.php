<?php

// app/Models/Rdv.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    protected $fillable = [
        'poseur_id',
        'client_id',
        'start_time',
        'end_time',
        'indisponible_poseur',
        'ga_gestion',
        'status'
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
}