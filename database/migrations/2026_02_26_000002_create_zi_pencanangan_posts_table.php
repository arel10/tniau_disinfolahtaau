<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('zi_perancangan_posts')) {
            Schema::create('zi_perancangan_posts', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->longText('konten')->nullable();
                $table->string('pdf_path')->nullable();
                $table->string('pdf_label')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zi_perancangan_posts');
    }
};
