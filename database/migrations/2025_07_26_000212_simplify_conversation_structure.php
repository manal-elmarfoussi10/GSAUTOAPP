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
        // ❌ Supprime cette ligne — elle provoque l’erreur car la colonne existe déjà
        // Schema::table('emails', function (Blueprint $table) {
        //     $table->foreignId('thread_id')->nullable()->after('client_id');
        // });

        // ✅ Crée la table conversation_threads si pas encore créée
        if (!Schema::hasTable('conversation_threads')) {
            Schema::create('conversation_threads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained();
                $table->foreignId('company_id')->constrained();
                $table->string('subject');
                $table->timestamps();
            });
        }

        // ✅ Ajoute la clé étrangère sur une colonne déjà existante
        Schema::table('emails', function (Blueprint $table) {
            // Supprimer la clé étrangère si elle existe
            try {
                $table->dropForeign(['thread_id']);
            } catch (\Throwable $e) {
                // Ignore si la clé n'existe pas encore
            }

            $table->foreign('thread_id')->references('id')->on('conversation_threads')->nullOnDelete();
        });
    }
    
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropForeign(['thread_id']);
            $table->dropColumn('thread_id');
        });
        
        Schema::dropIfExists('conversation_threads');
    }
};
