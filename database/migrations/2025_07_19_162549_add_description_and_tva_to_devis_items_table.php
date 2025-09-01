<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // In the migration file
public function up()
{
    Schema::table('devis_items', function (Blueprint $table) {
        $table->text('description')->nullable()->after('produit');
        $table->decimal('taux_tva', 5, 2)->default(0)->after('prix_unitaire');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis_items', function (Blueprint $table) {
            //
        });
    }
};
