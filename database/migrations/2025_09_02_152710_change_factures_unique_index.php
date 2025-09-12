<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            // 1) Drop the old global unique(index) on 'numero'
            // If your index name differs, see the note below.
            try {
                $table->dropUnique('factures_numero_unique');
            } catch (\Throwable $e) {
                // Fallback: try the “by columns” drop — works if index was created by Laravel convention
                try { $table->dropUnique(['numero']); } catch (\Throwable $ignored) {}
            }

            // 2) Create a composite unique per company
            $table->unique(['company_id', 'numero'], 'factures_company_numero_unique');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropUnique('factures_company_numero_unique');
            $table->unique('numero', 'factures_numero_unique');
        });
    }
};