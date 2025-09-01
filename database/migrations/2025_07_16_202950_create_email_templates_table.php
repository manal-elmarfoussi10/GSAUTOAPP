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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // CONFIRMATION RENDEZ-VOUS CLIENT
            $table->string('subject'); // [MONTECH AUTOMOBILES] - Votre rendez-vous ...
            $table->longText('body'); // Full email content with HTML
            $table->string('file_path')->nullable(); // uploaded file path
            $table->string('file_name')->nullable(); // visible file name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
