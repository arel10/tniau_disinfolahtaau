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
        Schema::table('media_sosials', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('nama');
            $table->string('logo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_sosials', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
