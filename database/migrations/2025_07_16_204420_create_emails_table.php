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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('sender')->nullable();       // Who sent it
            $table->string('receiver')->nullable();     // Who received
            $table->string('subject');
            $table->string('tag')->nullable();          // Label like "Work", "Social", etc.
            $table->text('content')->nullable();        // Email body
            $table->boolean('starred')->default(false); // Marked important
            $table->enum('folder', ['inbox', 'sent', 'important', 'bin'])->default('inbox');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
