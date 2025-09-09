<?php

// app/Models/Devis.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\CompanyScoped; 

class Devis extends Model
{
    use HasFactory;
    use CompanyScoped;

    protected $fillable = [
        'client_id', 
        'prospect_name',
        'prospect_email',
        'prospect_phone',
        'titre', 
        'date_devis', 
        'date_validite',
        'total_ht',
        'total_tva',
        'total_ttc',
        'company_id'
        
    ];
    public function getDisplayClientNameAttribute(): string
    {
        return $this->client?->nom_assure
            ?? $this->prospect_name
            ?? $this->titre
            ?? '-';
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(DevisItem::class);
    }
    public function company() { return $this->belongsTo(Company::class); }
}


