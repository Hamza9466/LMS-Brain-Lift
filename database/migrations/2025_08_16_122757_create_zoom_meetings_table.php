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
        Schema::create('zoom_meetings', function (Blueprint $table) {
                $table->id();
        $table->string('title');
        $table->string('meeting_id')->unique();
        $table->dateTime('starts_at');
        $table->unsignedInteger('duration_minutes')->default(30);
        $table->string('image_path')->nullable();
        $table->text('description')->nullable();
        $table->boolean('is_published')->default(true);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_meetings');
    }
};