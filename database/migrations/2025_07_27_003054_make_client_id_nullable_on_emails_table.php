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
        // If you haven’t installed the DBAL package yet, do:
        // composer require doctrine/dbal
        Schema::table('emails', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')
                  ->nullable(false)
                  ->change();
        });
    }
};
