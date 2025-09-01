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
        Schema::create('bon_de_commande_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('produit_libelle')->nullable();
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 2)->default(0);
            $table->decimal('remise', 5, 2)->default(0);
            $table->boolean('ajouter_au_stock')->default(false);
    
            $table->foreignId('bon_de_commande_id')
                  ->constrained('bons_de_commande')
                  ->cascadeOnDelete();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_de_commande_lignes');
    }
};
