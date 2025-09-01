<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('emails', function (Blueprint $table) {
        $table->string('file_path')->nullable()->after('is_read');
        $table->string('file_name')->nullable()->after('file_path');
    });
}

public function down(): void
{
    Schema::table('emails', function (Blueprint $table) {
        $table->dropColumn(['file_path', 'file_name']);
    });
}
};
