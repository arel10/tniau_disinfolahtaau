<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_sosials', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();       // nama platform (opsional)
            $table->string('logo');                   // path gambar logo
            $table->string('link')->nullable();       // URL tujuan
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_sosials');
    }
};
