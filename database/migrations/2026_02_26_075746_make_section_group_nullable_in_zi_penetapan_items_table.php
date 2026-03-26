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
        Schema::table('zi_penetapan_items', function (Blueprint $table) {
            $table->string('section_group')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zi_penetapan_items', function (Blueprint $table) {
            $table->string('section_group')->nullable(false)->change();
        });
    }
};
