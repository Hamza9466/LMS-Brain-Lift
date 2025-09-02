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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
    $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->timestamp('started_at')->nullable();
    $table->timestamp('submitted_at')->nullable();
    $table->enum('status', ['in_progress','submitted'])->default('in_progress');
    $table->decimal('score', 8, 2)->default(0);
    $table->decimal('percentage', 5, 2)->default(0);
    $table->boolean('is_passed')->default(false);
    $table->unsignedInteger('duration_seconds')->default(0);
    $table->string('ip_address', 45)->nullable();
    $table->timestamps();

    $table->index(['quiz_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};