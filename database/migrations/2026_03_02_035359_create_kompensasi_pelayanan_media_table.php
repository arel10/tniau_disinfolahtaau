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
        Schema::create('kompensasi_pelayanan_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kompensasi_pelayanan_id')->constrained('kompensasi_pelayanan')->onDelete('cascade');
            $table->string('file_path');
            $table->enum('type', ['image', 'video', 'pdf']);
            $table->string('original_name')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompensasi_pelayanan_media');
    }
};
