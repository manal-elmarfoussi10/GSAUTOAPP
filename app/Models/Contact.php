<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'message', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

