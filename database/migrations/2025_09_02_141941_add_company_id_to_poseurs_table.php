<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('poseurs', function (Blueprint $table) {
            if (!Schema::hasColumn('poseurs', 'company_id')) {
                $table->foreignId('company_id')
                    ->nullable()                               // keep it nullable for the backfill step
                    ->constrained('companies')
                    ->cascadeOnDelete()
                    ->after('id');

                $table->index('company_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poseurs', function (Blueprint $table) {
            if (Schema::hasColumn('poseurs', 'company_id')) {
                $table->dropConstrainedForeignId('company_id');
            }
        });
    }
};