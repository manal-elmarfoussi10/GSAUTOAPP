<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('paiements', function (Blueprint $table) {
        $table->foreignId('avoir_id')->nullable()->constrained('avoirs')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('paiements', function (Blueprint $table) {
        $table->dropForeign(['avoir_id']);
        $table->dropColumn('avoir_id');
    });
}
};
