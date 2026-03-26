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
        Schema::table('e_library_documents', function (Blueprint $table) {
            // Add status column: published, draft, private
            $table->string('status', 20)->default('published')->after('is_published');
        });

        // Migrate existing data: is_published=true -> 'published', false -> 'draft'
        \DB::table('e_library_documents')->where('is_published', true)->update(['status' => 'published']);
        \DB::table('e_library_documents')->where('is_published', false)->update(['status' => 'draft']);

        Schema::table('e_library_documents', function (Blueprint $table) {
            $table->dropColumn('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('e_library_documents', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('cover_path');
        });

        \DB::table('e_library_documents')->where('status', 'published')->update(['is_published' => true]);
        \DB::table('e_library_documents')->where('status', '!=', 'published')->update(['is_published' => false]);

        Schema::table('e_library_documents', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
