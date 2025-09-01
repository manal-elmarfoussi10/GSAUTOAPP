<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatorIdToConversationThreadsTable extends Migration
{
    public function up()
    {
        Schema::table('conversation_threads', function (Blueprint $table) {
            $table->unsignedBigInteger('creator_id')->after('subject')->nullable();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('conversation_threads', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropColumn('creator_id');
        });
    }
}