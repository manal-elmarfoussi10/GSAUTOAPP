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
        Schema::table('replies', function (Blueprint $table) {
            $table->unsignedBigInteger('thread_id')->nullable()->after('email_id');
    
            $table->foreign('thread_id')->references('id')->on('conversation_threads')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropForeign(['thread_id']);
            $table->dropColumn('thread_id');
        });
    }
};
