<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // UUID public identifier — add column first (nullable, no unique yet)
            $table->uuid('uuid')->nullable()->after('id');

            // Two-Factor Authentication (TOTP)
            $table->text('two_factor_secret')->nullable()->after('remember_token');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');

            // Login protection
            $table->unsignedTinyInteger('failed_login_count')->default(0)->after('two_factor_confirmed_at');
            $table->timestamp('locked_until')->nullable()->after('failed_login_count');
            $table->timestamp('password_changed_at')->nullable()->after('locked_until');
        });

        // Populate UUIDs for existing users
        DB::table('users')->whereNull('uuid')->orWhere('uuid', '')->orderBy('id')->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update(['uuid' => (string) Str::uuid()]);
        });

        // Now add unique constraint
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'uuid',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'failed_login_count',
                'locked_until',
                'password_changed_at',
            ]);
        });
    }
};
