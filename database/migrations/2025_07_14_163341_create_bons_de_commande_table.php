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
        Schema::create('bons_de_commande', function (Blueprint $table) {
            $table->id();
    
            // Foreign keys
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fournisseur_id')->constrained()->cascadeOnDelete();
    
            // Optional info
            $table->string('titre')->nullable();
            $table->string('fichier')->nullable();
    
            // Date
            $table->date('date_commande');
    
            // Totals
            $table->decimal('total_ht', 10, 2)->default(0);
            $table->decimal('tva', 5, 2)->default(20);
            $table->decimal('montant_tva', 10, 2)->default(0);
            $table->decimal('total_ttc', 10, 2)->default(0);
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bons_de_commande');
    }
};
