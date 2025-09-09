<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (!Schema::hasColumn('produits', 'company_id')) {
                $table->foreignId('company_id')
                      ->nullable()
                      ->constrained()
                      ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (Schema::hasColumn('produits', 'company_id')) {
                // for MySQL you must drop FK before column:
                $table->dropConstrainedForeignId('company_id'); // drops FK + column
                // or: $table->dropForeign(['company_id']); $table->dropColumn('company_id');
            }
        });
    }
};
