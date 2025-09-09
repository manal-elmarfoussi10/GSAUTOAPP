<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            // Add nullable first so migrate won’t fail with existing rows
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id'); // drops FK + column
        });
    }
};
