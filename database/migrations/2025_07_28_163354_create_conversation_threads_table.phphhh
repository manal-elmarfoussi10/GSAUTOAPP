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
        Schema::create('conversation_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('subject')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_threads');
    }
};
