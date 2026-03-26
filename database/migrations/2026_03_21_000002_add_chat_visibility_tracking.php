<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            // Track if chat is hidden from visitor (due to idle/page leave)
            if (!Schema::hasColumn('chat_sessions', 'hidden_for_visitor')) {
                $table->boolean('hidden_for_visitor')->default(false)->after('status');
            }

            // Last timestamp when visitor was actively using chat
            if (!Schema::hasColumn('chat_sessions', 'last_seen_by_visitor_at')) {
                $table->timestamp('last_seen_by_visitor_at')->nullable()->after('last_activity_at');
            }

            // Last timestamp when admin replied to this session
            if (!Schema::hasColumn('chat_sessions', 'last_reply_by_admin_at')) {
                $table->timestamp('last_reply_by_admin_at')->nullable()->after('last_seen_by_visitor_at');
            }

            // Track when visitor left the page/became idle
            if (!Schema::hasColumn('chat_sessions', 'visitor_left_page_at')) {
                $table->timestamp('visitor_left_page_at')->nullable()->after('last_reply_by_admin_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $columns = ['hidden_for_visitor', 'last_seen_by_visitor_at', 'last_reply_by_admin_at', 'visitor_left_page_at'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('chat_sessions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
