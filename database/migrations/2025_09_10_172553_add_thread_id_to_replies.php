<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('replies', function (Blueprint $table) {
            if (! Schema::hasColumn('replies', 'thread_id')) {
                $table->foreignId('thread_id')
                      ->nullable()
                      ->constrained('conversation_threads')
                      ->nullOnDelete()
                      ->after('email_id');
            }
        });

        // Optional backfill if you previously stored a conversation_id
        if (Schema::hasColumn('replies', 'conversation_id')) {
            DB::statement('UPDATE replies SET thread_id = conversation_id WHERE thread_id IS NULL');
        }
    }

    public function down(): void
    {
        Schema::table('replies', function (Blueprint $table) {
            if (Schema::hasColumn('replies', 'thread_id')) {
                $table->dropForeign(['thread_id']);
                $table->dropColumn('thread_id');
            }
        });
    }
};