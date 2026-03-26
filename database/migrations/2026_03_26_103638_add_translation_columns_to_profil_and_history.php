<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add translation columns to profil_contents and history_diagrams tables.
     * These columns store pre-translated content for each supported locale.
     */
    public function up(): void
    {
        $locales = ['en', 'ar', 'fr', 'es', 'ru', 'ja'];

        // profil_contents: translate title and content
        Schema::table('profil_contents', function (Blueprint $table) use ($locales) {
            foreach ($locales as $locale) {
                $table->string("title_{$locale}")->nullable()->after('title');
            }
            foreach ($locales as $locale) {
                $table->longText("content_{$locale}")->nullable()->after('content');
            }
        });

        // history_diagrams: translate title and description
        Schema::table('history_diagrams', function (Blueprint $table) use ($locales) {
            foreach ($locales as $locale) {
                $table->string("title_{$locale}")->nullable()->after('title');
            }
            foreach ($locales as $locale) {
                $table->text("description_{$locale}")->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        $locales = ['en', 'ar', 'fr', 'es', 'ru', 'ja'];

        Schema::table('profil_contents', function (Blueprint $table) use ($locales) {
            foreach ($locales as $locale) {
                $table->dropColumn("title_{$locale}");
                $table->dropColumn("content_{$locale}");
            }
        });

        Schema::table('history_diagrams', function (Blueprint $table) use ($locales) {
            foreach ($locales as $locale) {
                $table->dropColumn("title_{$locale}");
                $table->dropColumn("description_{$locale}");
            }
        });
    }
};
