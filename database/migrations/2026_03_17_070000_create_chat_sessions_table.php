<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_token', 100)->index();
            $table->string('visitor_name', 120)->nullable();
            $table->enum('status', ['active', 'inactive', 'expired', 'closed'])->default('active')->index();
            $table->timestamp('admin_read_at')->nullable()->index();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamp('last_activity_at')->nullable()->index();
            $table->timestamps();

            $table->index(['visitor_token', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};
