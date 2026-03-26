<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_menu_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('widget_id')->constrained('custom_menu_widgets')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->enum('media_type', ['image', 'video', 'pdf', 'logo']);
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_menu_media');
    }
};
