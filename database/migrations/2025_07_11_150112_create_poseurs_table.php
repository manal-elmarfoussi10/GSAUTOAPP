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
        Schema::create('poseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('mot_de_passe')->nullable(); // Password for planning/calendar
            $table->boolean('actif')->default(true);
            $table->string('couleur')->default('#000000');
    
            // Adresse
            $table->string('rue')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
    
            // Infos supplémentaires
            $table->text('info')->nullable();
    
            // Département selection (stored as JSON array)
            $table->json('departements')->nullable();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poseurs');
    }
};
