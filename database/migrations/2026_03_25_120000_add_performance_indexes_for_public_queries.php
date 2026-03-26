<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('beritas')) {
            Schema::table('beritas', function (Blueprint $table) {
                $this->addIndexSafely($table, ['status', 'published_at', 'tanggal'], 'beritas_status_pub_tgl_idx');
                $this->addIndexSafely($table, ['status', 'views'], 'beritas_status_views_idx');
            });
        }

        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $this->addIndexSafely($table, ['is_published', 'tanggal_kegiatan', 'created_at'], 'events_publish_tgl_created_idx');
            });
        }

        if (Schema::hasTable('galeris')) {
            Schema::table('galeris', function (Blueprint $table) {
                $this->addIndexSafely($table, ['tipe', 'kategori_galeri', 'tanggal_kegiatan'], 'galeris_tipe_kat_tgl_idx');
            });
        }

        if (Schema::hasTable('event_media')) {
            Schema::table('event_media', function (Blueprint $table) {
                $this->addIndexSafely($table, ['event_id', 'section', 'type', 'position'], 'event_media_ev_sec_type_pos_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('beritas')) {
            Schema::table('beritas', function (Blueprint $table) {
                $this->dropIndexSafely($table, 'beritas_status_pub_tgl_idx');
                $this->dropIndexSafely($table, 'beritas_status_views_idx');
            });
        }

        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $this->dropIndexSafely($table, 'events_publish_tgl_created_idx');
            });
        }

        if (Schema::hasTable('galeris')) {
            Schema::table('galeris', function (Blueprint $table) {
                $this->dropIndexSafely($table, 'galeris_tipe_kat_tgl_idx');
            });
        }

        if (Schema::hasTable('event_media')) {
            Schema::table('event_media', function (Blueprint $table) {
                $this->dropIndexSafely($table, 'event_media_ev_sec_type_pos_idx');
            });
        }
    }

    private function addIndexSafely(Blueprint $table, array $columns, string $indexName): void
    {
        try {
            $table->index($columns, $indexName);
        } catch (\Throwable $e) {
            // Ignore duplicate/driver-specific index errors to keep migration idempotent.
        }
    }

    private function dropIndexSafely(Blueprint $table, string $indexName): void
    {
        try {
            $table->dropIndex($indexName);
        } catch (\Throwable $e) {
            // Ignore missing index errors on rollback.
        }
    }
};
