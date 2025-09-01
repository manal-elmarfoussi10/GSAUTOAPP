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
        Schema::table('emails', function (Blueprint $table) {
            $table->string('label_color')->nullable()->after('tag');
            $table->boolean('is_deleted')->default(false)->after('starred');
            $table->boolean('is_read')->default(false)->after('is_deleted');
        });
    }
    
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn(['label_color', 'is_deleted', 'is_read']);
        });
    }
};
