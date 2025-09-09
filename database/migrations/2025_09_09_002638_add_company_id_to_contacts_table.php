<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Nullable to keep old rows valid
            $table->unsignedBigInteger('company_id')->nullable()->after('message');

            // Index + FK (set null if company is deleted)
            $table->foreign('company_id')
                  ->references('id')->on('companies')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
