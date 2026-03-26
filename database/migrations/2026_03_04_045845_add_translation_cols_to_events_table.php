<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('nama_kegiatan_en')->nullable()->after('nama_kegiatan');
            $table->string('nama_kegiatan_ar')->nullable()->after('nama_kegiatan_en');
            $table->string('nama_kegiatan_fr')->nullable()->after('nama_kegiatan_ar');
            $table->string('nama_kegiatan_es')->nullable()->after('nama_kegiatan_fr');
            $table->string('nama_kegiatan_ru')->nullable()->after('nama_kegiatan_es');
            $table->string('nama_kegiatan_ja')->nullable()->after('nama_kegiatan_ru');
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
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['nama_kegiatan_en','nama_kegiatan_ar','nama_kegiatan_fr','nama_kegiatan_es','nama_kegiatan_ru','nama_kegiatan_ja','deskripsi_en','deskripsi_ar','deskripsi_fr','deskripsi_es','deskripsi_ru','deskripsi_ja']);
        });
    }
};
