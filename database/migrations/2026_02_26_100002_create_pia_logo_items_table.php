<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pia_logo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pia_page_id')->constrained('pia_pages')->cascadeOnDelete();
            $table->string('title');
            $table->string('link_url');
            $table->string('logo_path');
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pia_logo_items');
    }
};
