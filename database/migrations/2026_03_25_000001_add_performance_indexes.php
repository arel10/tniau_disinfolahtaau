<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Performance optimization: Add missing database indexes
 * for columns frequently used in WHERE, ORDER BY, and JOIN clauses.
 */
return new class extends Migration
{
    public function up(): void
    {
        // === beritas table ===
        // status + published_at: used by scopePublished() on every public page
        // views: used for popular/trending sorting
        // tanggal: used for latest() sorting
        // kategori_id already has index via foreignId constraint
        Schema::table('beritas', function (Blueprint $table) {
            $table->index(['status', 'published_at'], 'beritas_status_published_at_index');
            $table->index('views', 'beritas_views_index');
            $table->index('tanggal', 'beritas_tanggal_index');
        });

        // === kontaks table ===
        // status: used for filtering and counting in KontakController & DashboardController
        Schema::table('kontaks', function (Blueprint $table) {
            $table->index('status', 'kontaks_status_index');
        });

        // === galeris table ===
        // tipe: used for filtering foto/video counts
        // tanggal_kegiatan: used for ordering
        // kategori_galeri: used for filtering by category
        Schema::table('galeris', function (Blueprint $table) {
            $table->index('tipe', 'galeris_tipe_index');
            $table->index('tanggal_kegiatan', 'galeris_tanggal_kegiatan_index');
            $table->index('kategori_galeri', 'galeris_kategori_galeri_index');
            $table->index('group_id', 'galeris_group_id_index');
        });

        // === events table ===
        // is_published + tanggal_kegiatan: used on public event listing
        if (Schema::hasColumn('events', 'is_published')) {
            Schema::table('events', function (Blueprint $table) {
                $table->index(['is_published', 'tanggal_kegiatan'], 'events_published_tanggal_index');
            });
        }

        // === custom_menus table ===
        // slug + is_published: used for public page lookups
        if (Schema::hasColumn('custom_menus', 'slug')) {
            Schema::table('custom_menus', function (Blueprint $table) {
                $table->index('slug', 'custom_menus_slug_index');
                $table->index('is_published', 'custom_menus_is_published_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropIndex('beritas_status_published_at_index');
            $table->dropIndex('beritas_views_index');
            $table->dropIndex('beritas_tanggal_index');
        });

        Schema::table('kontaks', function (Blueprint $table) {
            $table->dropIndex('kontaks_status_index');
        });

        Schema::table('galeris', function (Blueprint $table) {
            $table->dropIndex('galeris_tipe_index');
            $table->dropIndex('galeris_tanggal_kegiatan_index');
            $table->dropIndex('galeris_kategori_galeri_index');
            $table->dropIndex('galeris_group_id_index');
        });

        if (Schema::hasColumn('events', 'is_published')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropIndex('events_published_tanggal_index');
            });
        }

        if (Schema::hasColumn('custom_menus', 'slug')) {
            Schema::table('custom_menus', function (Blueprint $table) {
                $table->dropIndex('custom_menus_slug_index');
                $table->dropIndex('custom_menus_is_published_index');
            });
        }
    }
};
