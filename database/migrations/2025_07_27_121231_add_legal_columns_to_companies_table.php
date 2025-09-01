<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('legal_form')->nullable();
            $table->decimal('capital', 15, 2)->nullable();
            $table->string('head_office_address')->nullable();
            $table->string('rcs_number')->nullable();
            $table->string('rcs_city')->nullable();
            $table->string('naf_code')->nullable();
            $table->string('professional_insurance')->nullable();
            $table->string('representative')->nullable();
            $table->string('tva_regime')->nullable();
            $table->string('eco_contribution')->nullable();
            $table->string('penalty_rate')->nullable();
            $table->string('methode_paiement')->nullable();
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'legal_form',
                'capital',
                'head_office_address',
                'rcs_number',
                'rcs_city',
                'naf_code',
                'professional_insurance',
                'representative',
                'tva_regime',
                'eco_contribution',
                'penalty_rate',
                'methode_paiement',
            ]);
        });
    }
};
