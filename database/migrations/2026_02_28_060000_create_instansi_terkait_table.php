<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instansi_terkait', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('logo')->nullable();   // path to logo file
            $table->string('link')->nullable();   // URL to the institution
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instansi_terkait');
    }
};
