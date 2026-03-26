<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom terjemahan untuk judul, ringkasan & konten.
     * Kolom asli (judul, ringkasan, konten) tetap sebagai bahasa Indonesia (default).
     * Kolom baru: _en (English), _ja (日本語).
     * Untuk locale lain (ar, fr, ru, es) fallback ke en → id.
     */
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            // English translations
            $table->string('judul_en', 255)->nullable()->after('judul');
            $table->text('ringkasan_en')->nullable()->after('ringkasan');
            $table->longText('konten_en')->nullable()->after('konten');

            // Japanese translations
            $table->string('judul_ja', 255)->nullable()->after('judul_en');
            $table->text('ringkasan_ja')->nullable()->after('ringkasan_en');
            $table->longText('konten_ja')->nullable()->after('konten_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn([
                'judul_en', 'judul_ja',
                'ringkasan_en', 'ringkasan_ja',
                'konten_en', 'konten_ja',
            ]);
        });
    }
};
