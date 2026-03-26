<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_menu_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('custom_menus')->cascadeOnDelete();
            $table->string('widget_type'); // judul, foto, video, pdf, deskripsi, link_url, logo, logo_link, tanggal, lokasi, email, no_hp
            $table->text('text_content')->nullable(); // for judul, deskripsi, link_url, logo_link, tanggal, lokasi, email, no_hp
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_menu_widgets');
    }
};
