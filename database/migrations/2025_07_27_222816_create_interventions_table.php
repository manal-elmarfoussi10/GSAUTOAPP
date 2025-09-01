<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poseur_id');     // FK vers users (rôle poseur)
            $table->unsignedBigInteger('client_id');     // FK vers clients
            $table->date('date');                        // date de l'intervention
            $table->text('commentaire')->nullable();     // commentaire libre
            $table->string('photo')->nullable();         // chemin photo (stockée)
            $table->timestamps();

            // Clés étrangères
            $table->foreign('poseur_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
