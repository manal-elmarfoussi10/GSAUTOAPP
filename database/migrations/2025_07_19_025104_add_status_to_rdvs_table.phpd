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
        Schema::table('rdvs', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('start_time');
        });
    }

    public function down(): void
    {
        Schema::table('rdvs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
