<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom_assure')->nullable();
            $table->string('prenom')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
            $table->string('plaque')->nullable();
            $table->string('nom_assurance')->nullable();
            $table->string('autre_assurance')->nullable();
            $table->boolean('ancien_modele_plaque')->default(false);
            $table->string('numero_police')->nullable();
            $table->date('date_sinistre')->nullable();
            $table->date('date_declaration')->nullable();
            $table->string('raison')->nullable();
            $table->string('type_vitrage')->nullable();
            $table->string('professionnel')->nullable();
        
            // Données facultatives
            $table->boolean('reparation')->default(false);
            $table->string('photo_vitrage')->nullable();
            $table->string('photo_carte_verte')->nullable();
            $table->string('photo_carte_grise')->nullable();
            $table->string('type_cadeau')->nullable();
            $table->string('numero_sinistre')->nullable();
            $table->string('kilometrage')->nullable();
            $table->string('connu_par')->nullable();
            $table->string('adresse_pose')->nullable();
            $table->string('reference_interne')->nullable();
            $table->string('reference_client')->nullable();
            $table->text('precision')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
