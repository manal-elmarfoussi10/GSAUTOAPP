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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();       // dossier
            $table->string('libelle_dossier')->nullable();             // if no dossier selected
            $table->unsignedBigInteger('fournisseur_id')->nullable();
            $table->unsignedBigInteger('poseur_id')->nullable();
            $table->unsignedBigInteger('produit_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('statut')->default('À COMMANDER');          // e.g., “À COMMANDER”, “COMMANDÉ”, etc.
            $table->boolean('accord')->default(false);                 // Checkbox
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->nullOnDelete();
            $table->foreign('poseur_id')->references('id')->on('poseurs')->nullOnDelete();
            $table->foreign('produit_id')->references('id')->on('produits')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
