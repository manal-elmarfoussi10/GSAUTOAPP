<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add company_id where missing
        $tables = [
            // tables that have client_id (easy backfill from clients)
            'devis',
            'factures',
            'expenses',
            'photos',
            'rdvs',
            'stocks',
            'intervention_photos',
            'emails',                 // if you tie emails to a client_id
            'conversations',          // if you tie conversations to a client_id
            'conversation_threads',   // same as above

            // tables that depend on a parent (we’ll backfill via the parent)
            'avoirs',                 // via factures
            'paiements',              // via factures (or clients if you use that)
            'bons_de_commande',      // via bons_de_commande
            'facture_items',          // via factures
            'devis_items',            // via devis

            // optional – only include if your Produits are per-company (not global)
            // 'produits',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (! Schema::hasColumn($table->getTable(), 'company_id')) {
                    $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
                }
            });
        }

        // 2) Backfill from clients where possible
        // Generic helper: backfill tables that have client_id -> clients.company_id
        $withClient = [
            'devis', 'factures', 'expenses', 'photos', 'rdvs', 'stocks',
            'intervention_photos', 'emails', 'conversations', 'conversation_threads',
            'bons_de_commande',
        ];
        foreach ($withClient as $t) {
            if (Schema::hasColumn($t, 'client_id')) {
                DB::statement("
                    UPDATE {$t} t
                    JOIN clients c ON c.id = t.client_id
                    SET t.company_id = c.company_id
                    WHERE t.company_id IS NULL
                ");
            }
        }

        // Backfill avoirs via factures
        if (Schema::hasTable('avoirs') && Schema::hasColumn('avoirs', 'facture_id')) {
            DB::statement("
                UPDATE avoirs a
                JOIN factures f ON f.id = a.facture_id
                SET a.company_id = f.company_id
                WHERE a.company_id IS NULL
            ");
        }

        // Backfill paiements via factures (adjust if you use client_id instead)
        if (Schema::hasTable('paiements')) {
            if (Schema::hasColumn('paiements', 'facture_id')) {
                DB::statement("
                    UPDATE paiements p
                    JOIN factures f ON f.id = p.facture_id
                    SET p.company_id = f.company_id
                    WHERE p.company_id IS NULL
                ");
            } elseif (Schema::hasColumn('paiements', 'client_id')) {
                DB::statement("
                    UPDATE paiements p
                    JOIN clients c ON c.id = p.client_id
                    SET p.company_id = c.company_id
                    WHERE p.company_id IS NULL
                ");
            }
        }

       

        // facture_items via factures
        if (
            Schema::hasTable('facture_items')
            && Schema::hasColumn('facture_items', 'facture_id')
        ) {
            DB::statement("
                UPDATE facture_items i
                JOIN factures f ON f.id = i.facture_id
                SET i.company_id = f.company_id
                WHERE i.company_id IS NULL
            ");
        }

        // devis_items via devis
        if (
            Schema::hasTable('devis_items')
            && Schema::hasColumn('devis_items', 'devis_id')
        ) {
            DB::statement("
                UPDATE devis_items i
                JOIN devis d ON d.id = i.devis_id
                SET i.company_id = d.company_id
                WHERE i.company_id IS NULL
            ");
        }

        // produits (only if produits are per-company in your business rules)
        // if (Schema::hasTable('produits')) {
        //     // If you can derive produit->company_id from somewhere, do it here.
        //     // Otherwise leave null and set on creation with the trait.
        // }
    }

    public function down(): void
    {
        $tables = [
            'devis','factures','expenses','photos','rdvs','stocks','intervention_photos',
            'emails','conversations','conversation_threads',
            'avoirs','paiements','bons_de_commande',
            'facture_items','devis_items',
            // 'produits',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'company_id')) {
                    $table->dropConstrainedForeignId('company_id');
                }
            });
        }
    }
};