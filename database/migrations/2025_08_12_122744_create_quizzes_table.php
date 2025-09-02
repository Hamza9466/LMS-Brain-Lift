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
        Schema::create('quizzes', function (Blueprint $table) {
          $table->id();
    $table->foreignId('course_id')->constrained()->cascadeOnDelete();

     $table->foreignId('section_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
    
    $table->string('title');
    $table->text('description')->nullable();
    $table->unsignedInteger('duration_minutes')->nullable(); // null = no time limit
    $table->unsignedTinyInteger('max_attempts')->default(1);
    $table->decimal('pass_percentage', 5, 2)->default(70);   // 0..100
    $table->boolean('shuffle_questions')->default(true);
    $table->boolean('shuffle_options')->default(true);
    $table->boolean('is_published')->default(false);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};