<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Add the column (no AFTER)
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'units')) {
                $table->integer('units')->default(0);
            }
        });

        // 2) Optional backfill from users.units (pick the rule you want)
        // Here: take MAX(units) per company; change to MIN or admin-specific if needed.
        DB::statement("
            UPDATE companies c
            LEFT JOIN (
                SELECT company_id, COALESCE(MAX(units), 0) AS u
                FROM users
                WHERE company_id IS NOT NULL
                GROUP BY company_id
            ) x ON x.company_id = c.id
            SET c.units = COALESCE(x.u, 0)
        ");
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'units')) {
                $table->dropColumn('units');
            }
        });
    }
};
