<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->string('prospect_name')->nullable()->after('client_id');
            $table->string('prospect_email')->nullable()->after('prospect_name');
            $table->string('prospect_phone')->nullable()->after('prospect_email');
        });
    }

    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn(['prospect_name','prospect_email','prospect_phone']);
        });
    }
};
