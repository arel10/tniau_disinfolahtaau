<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('judul_en')->nullable()->after('judul');
            $table->string('judul_ar')->nullable()->after('judul_en');
            $table->string('judul_fr')->nullable()->after('judul_ar');
            $table->string('judul_es')->nullable()->after('judul_fr');
            $table->string('judul_ru')->nullable()->after('judul_es');
            $table->string('judul_ja')->nullable()->after('judul_ru');
            $table->text('deskripsi_en')->nullable()->after('deskripsi');
            $table->text('deskripsi_ar')->nullable()->after('deskripsi_en');
            $table->text('deskripsi_fr')->nullable()->after('deskripsi_ar');
            $table->text('deskripsi_es')->nullable()->after('deskripsi_fr');
            $table->text('deskripsi_ru')->nullable()->after('deskripsi_es');
            $table->text('deskripsi_ja')->nullable()->after('deskripsi_ru');
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn(['judul_en','judul_ar','judul_fr','judul_es','judul_ru','judul_ja','deskripsi_en','deskripsi_ar','deskripsi_fr','deskripsi_es','deskripsi_ru','deskripsi_ja']);
        });
    }
};
