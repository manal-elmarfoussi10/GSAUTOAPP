<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('statut_signature')->default(false);
            $table->boolean('statut_verif_bdg')->default(false);
            $table->boolean('statut_envoi')->default(false);
            $table->boolean('statut_relance')->default(false);
            $table->boolean('statut_termine')->default(false);
        });
    }

    public function down(): void {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['statut_signature', 'statut_verif_bdg', 'statut_envoi', 'statut_relance', 'statut_termine']);
        });
    }
};
