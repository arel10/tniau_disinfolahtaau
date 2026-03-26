<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('otp_code', 10)->nullable()->after('phone');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->string('otp_channel', 20)->nullable()->after('otp_expires_at');
            $table->unsignedTinyInteger('otp_attempts')->default(0)->after('otp_channel');
        });

        // Backfill: set username = name (slugified) for existing users
        DB::table('users')->whereNull('username')->get()->each(function ($user) {
            $base = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $user->name));
            $username = $base ?: 'user' . $user->id;
            // ensure uniqueness
            $exists = DB::table('users')->where('username', $username)->exists();
            if ($exists) {
                $username = $username . $user->id;
            }
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'phone', 'otp_code', 'otp_expires_at', 'otp_channel', 'otp_attempts']);
        });
    }
};
