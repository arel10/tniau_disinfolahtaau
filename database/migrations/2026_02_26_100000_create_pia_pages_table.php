<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pia_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->default('PIA');
            $table->string('history_title')->nullable();
            $table->longText('history_content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pia_pages');
    }
};
