<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CompanyScoped; 

class Expense extends Model
{
    use CompanyScoped;
    protected $fillable = [
        'date',
        'client_id',
        'fournisseur_id',
        'paid_status',
        'ht_amount',
        'ttc_amount',
        'description' ,
        'company_id'// Add this
    ];
    
    // Add this casting
    protected $casts = [
        'date' => 'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
    public function company() { return $this->belongsTo(Company::class); }
}
