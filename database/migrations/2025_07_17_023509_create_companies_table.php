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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la société
            $table->string('commercial_name')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('siret')->nullable();
            $table->string('tva')->nullable();
            $table->string('iban')->nullable();
            $table->string('bic')->nullable();
            $table->string('ape')->nullable();
            $table->text('address')->nullable(); // You can normalize address if needed
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('logo')->nullable(); // logo file path
            $table->string('rib')->nullable();   // rib document path
            $table->string('kbis')->nullable();  // kbis document path
            $table->string('id_photo_recto')->nullable();
            $table->string('id_photo_verso')->nullable();
            $table->string('tva_exemption_doc')->nullable();
            $table->string('invoice_terms_doc')->nullable();
            $table->string('known_by')->nullable();
            $table->string('contact_permission')->default('ask');
            $table->string('garage_type')->default('both');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
