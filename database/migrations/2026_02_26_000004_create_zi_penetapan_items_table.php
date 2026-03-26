<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('zi_penetapan_items')) {
            Schema::create('zi_penetapan_items', function (Blueprint $table) {
                $table->id();
                $table->string('section_group'); // pengungkit | hasil
                $table->string('judul');
                $table->integer('persen')->default(0);
                $table->string('foto')->nullable();
                $table->longText('konten')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zi_penetapan_items');
    }
};
