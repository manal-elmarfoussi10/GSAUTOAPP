<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rdv;
use App\Models\company;
use App\Models\ConversationThread;
use App\Models\Email;


class Client extends Model
{
    protected $fillable = [
        'nom_assure', 'prenom', 'email', 'telephone', 'adresse',
        'code_postal', 'ville', 'plaque', 'nom_assurance', 'autre_assurance',
        'ancien_modele_plaque', 'numero_police', 'date_sinistre', 'date_declaration',
        'raison', 'type_vitrage', 'professionnel', 'reparation',
        'photo_vitrage', 'photo_carte_verte', 'photo_carte_grise',
        'type_cadeau', 'numero_sinistre', 'kilometrage', 'connu_par',
        'adresse_pose', 'reference_interne', 'reference_client', 'precision', 'statut'
    ];
    public function rdvs()
{
    return $this->hasMany(Rdv::class);
}

public function factures()
{
    return $this->hasMany(Facture::class);
}

public function avoirs()
{
    return $this->hasManyThrough(
        \App\Models\Avoir::class,
        \App\Models\Facture::class,
        'client_id',    // Foreign key on factures table
        'facture_id',   // Foreign key on avoirs table
        'id',           // Local key on clients table
        'id'            // Local key on factures table
    );
}

public function devis()
{
    return $this->hasMany(Devis::class);
}
public function photos()
{
    return $this->hasMany(Photo::class);
}

public function expenses()
{
    return $this->hasMany(Expense::class);
}
public function bondecommandes()
{
    return $this->hasMany(BonDeCommande::class);
}
public function conversations()
{
    return $this->hasMany(ConversationThread::class);
}

public function company()
{
    return $this->belongsTo(Company::class);
}
/**
 * All email messages tied to this client.
 */
public function emails()
{
    return $this->hasMany(Email::class, 'client_id');
}
public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}
