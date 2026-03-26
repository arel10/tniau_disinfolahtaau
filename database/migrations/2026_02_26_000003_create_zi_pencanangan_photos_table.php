<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('zi_perancangan_photos')) {
            Schema::create('zi_perancangan_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('zi_perancangan_posts')->cascadeOnDelete();
                $table->string('path');
                $table->string('caption')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zi_perancangan_photos');
    }
};
