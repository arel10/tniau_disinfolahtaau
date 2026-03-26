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
        Schema::create('zi_page_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zi_page_id');
            $table->string('file_path');
            $table->string('tipe')->default('image'); // image, video
            $table->timestamps();

            $table->foreign('zi_page_id')->references('id')->on('zi_pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zi_page_media');
    }
};
