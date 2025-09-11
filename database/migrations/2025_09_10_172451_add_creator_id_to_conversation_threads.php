<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('conversation_threads', function (Blueprint $table) {
            if (! Schema::hasColumn('conversation_threads', 'creator_id')) {
                $table->foreignId('creator_id')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete()
                      ->after('subject');
            }
        });
    }

    public function down(): void
    {
        Schema::table('conversation_threads', function (Blueprint $table) {
            if (Schema::hasColumn('conversation_threads', 'creator_id')) {
                $table->dropForeign(['creator_id']);
                $table->dropColumn('creator_id');
            }
        });
    }
};