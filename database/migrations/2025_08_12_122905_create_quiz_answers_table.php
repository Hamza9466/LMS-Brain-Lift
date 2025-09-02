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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
    $table->foreignId('attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
    $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
    $table->json('selected_option_ids')->nullable(); // store selected option IDs
    $table->decimal('points_awarded', 6, 2)->default(0);
    $table->timestamps();

    $table->unique(['attempt_id','question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};