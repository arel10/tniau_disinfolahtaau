<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create a table to store baseline adjustments for statistics
        // This ensures that manual manipulations are treated as starting points (baselines)
        // and don't interfere with automatic incrementing
        Schema::create('statistics_baselines', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique(); // e.g., 'visitors_total', 'views_total', 'berita_id_123'
            $table->bigInteger('baseline_value')->default(0); // The manually set starting point
            $table->text('notes')->nullable(); // Reason for adjustment
            $table->timestamp('adjusted_at')->nullable(); // When it was last adjusted
            $table->string('adjusted_by', 100)->nullable(); // Who adjusted it
            $table->timestamps();

            $table->index('key');
        });

        // Add baseline tracking to Visitor table if not exists
        if (!Schema::hasColumn('visitors', 'baseline_value')) {
            Schema::table('visitors', function (Blueprint $table) {
                $table->bigInteger('baseline_value')->default(0)->after('visited_at');
                $table->text('baseline_notes')->nullable()->after('baseline_value');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics_baselines');

        if (Schema::hasTable('visitors')) {
            Schema::table('visitors', function (Blueprint $table) {
                if (Schema::hasColumn('visitors', 'baseline_value')) {
                    $table->dropColumn('baseline_value');
                }
                if (Schema::hasColumn('visitors', 'baseline_notes')) {
                    $table->dropColumn('baseline_notes');
                }
            });
        }
    }
};
