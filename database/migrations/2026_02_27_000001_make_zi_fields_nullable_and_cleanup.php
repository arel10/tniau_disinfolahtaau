<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // zi_pages: judul nullable
        Schema::table('zi_pages', function (Blueprint $table) {
            $table->string('judul')->nullable()->change();
        });

        // zi_perancangan_posts: judul nullable
        Schema::table('zi_perancangan_posts', function (Blueprint $table) {
            $table->string('judul')->nullable()->change();
        });

        // zi_penetapan_items: judul nullable, persen nullable + drop default, drop sort_order
        Schema::table('zi_penetapan_items', function (Blueprint $table) {
            $table->string('judul')->nullable()->change();
            $table->integer('persen')->nullable()->default(null)->change();
        });

        if (Schema::hasColumn('zi_penetapan_items', 'sort_order')) {
            Schema::table('zi_penetapan_items', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }

    public function down(): void
    {
        Schema::table('zi_pages', function (Blueprint $table) {
            $table->string('judul')->nullable(false)->change();
        });

        Schema::table('zi_perancangan_posts', function (Blueprint $table) {
            $table->string('judul')->nullable(false)->change();
        });

        Schema::table('zi_penetapan_items', function (Blueprint $table) {
            $table->string('judul')->nullable(false)->change();
            $table->integer('persen')->nullable(false)->default(0)->change();
            if (!Schema::hasColumn('zi_penetapan_items', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
        });
    }
};
