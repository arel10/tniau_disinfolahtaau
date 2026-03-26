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
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('id')->index();
        });

        // Optionally backfill existing rows so each item has its own group_id (set to its id)
        // This helps existing records behave as single-item groups.
        if (Schema::hasTable('galeris')) {
            \DB::table('galeris')->whereNull('group_id')->chunkById(100, function ($rows) {
                foreach ($rows as $r) {
                    \DB::table('galeris')->where('id', $r->id)->update(['group_id' => (string) $r->id]);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
};
