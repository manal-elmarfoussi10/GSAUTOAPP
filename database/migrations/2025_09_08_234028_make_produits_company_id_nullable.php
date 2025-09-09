<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Make company_id nullable so we can store NULL for global products
            $table->unsignedBigInteger('company_id')->nullable()->change();

            // Optional: Add index for queries
            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable(false)->change();
            $table->dropIndex(['company_id']);
        });
    }
};