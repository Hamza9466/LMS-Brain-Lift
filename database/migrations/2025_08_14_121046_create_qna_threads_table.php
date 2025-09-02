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
        Schema::create('qna_threads', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->unsignedBigInteger('user_id'); // who asked
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->enum('status', ['open','closed'])->default('open');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['course_id','lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qna_threads');
    }
};