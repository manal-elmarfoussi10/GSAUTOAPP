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
            if (Schema::hasColumn('emails', 'conversation_id')) {
                DB::statement('ALTER TABLE emails DROP FOREIGN KEY IF EXISTS emails_conversation_id_foreign');
            }
        });
    }
    
    public function down()
    {
      
    }
};
