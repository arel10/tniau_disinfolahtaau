<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('zi_pages')) {
            Schema::create('zi_pages', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // zona_integritas | pembangunan | pemantauan
                $table->string('judul');
                $table->longText('konten')->nullable();
                $table->string('gambar')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zi_pages');
    }
};
