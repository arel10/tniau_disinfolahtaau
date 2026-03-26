<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pia_history_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pia_page_id')->constrained('pia_pages')->cascadeOnDelete();
            $table->string('old_history_title')->nullable();
            $table->longText('old_history_content')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamp('edited_at')->useCurrent();
            $table->timestamps();

            $table->foreign('edited_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pia_history_revisions');
    }
};
