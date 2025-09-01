<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'poseur_id',
        'client_id',
        'titre',
        'date',
        'commentaire',
        'photo'
    ];

    public function poseur()
    {
        return $this->belongsTo(User::class, 'poseur_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }
}
