<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            // Add missing locale columns for ar, fr, es, ru
            // (en and ja already exist)
            if (!Schema::hasColumn('beritas', 'judul_ar')) {
                $table->text('judul_ar')->nullable()->after('judul_en');
            }
            if (!Schema::hasColumn('beritas', 'judul_fr')) {
                $table->text('judul_fr')->nullable()->after('judul_ar');
            }
            if (!Schema::hasColumn('beritas', 'judul_es')) {
                $table->text('judul_es')->nullable()->after('judul_fr');
            }
            if (!Schema::hasColumn('beritas', 'judul_ru')) {
                $table->text('judul_ru')->nullable()->after('judul_es');
            }

            if (!Schema::hasColumn('beritas', 'ringkasan_ar')) {
                $table->text('ringkasan_ar')->nullable()->after('ringkasan_en');
            }
            if (!Schema::hasColumn('beritas', 'ringkasan_fr')) {
                $table->text('ringkasan_fr')->nullable()->after('ringkasan_ar');
            }
            if (!Schema::hasColumn('beritas', 'ringkasan_es')) {
                $table->text('ringkasan_es')->nullable()->after('ringkasan_fr');
            }
            if (!Schema::hasColumn('beritas', 'ringkasan_ru')) {
                $table->text('ringkasan_ru')->nullable()->after('ringkasan_es');
            }

            if (!Schema::hasColumn('beritas', 'konten_ar')) {
                $table->longText('konten_ar')->nullable()->after('konten_en');
            }
            if (!Schema::hasColumn('beritas', 'konten_fr')) {
                $table->longText('konten_fr')->nullable()->after('konten_ar');
            }
            if (!Schema::hasColumn('beritas', 'konten_es')) {
                $table->longText('konten_es')->nullable()->after('konten_fr');
            }
            if (!Schema::hasColumn('beritas', 'konten_ru')) {
                $table->longText('konten_ru')->nullable()->after('konten_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $cols = ['judul_ar','judul_fr','judul_es','judul_ru',
                     'ringkasan_ar','ringkasan_fr','ringkasan_es','ringkasan_ru',
                     'konten_ar','konten_fr','konten_es','konten_ru'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('beritas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
