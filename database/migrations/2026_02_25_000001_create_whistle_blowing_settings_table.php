<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whistle_blowing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gambar'); // Path to the image
            $table->string('link_tujuan'); // URL to redirect when image is clicked
            $table->string('judul')->nullable(); // Optional title
            $table->text('deskripsi')->nullable(); // Optional description
            $table->boolean('is_active')->default(true); // Active status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whistle_blowing_settings');
    }
};
