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
        Schema::create('personal_discounts', function (Blueprint $table) {
             $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   // student id
            $table->foreignId('course_id')->constrained()->cascadeOnDelete(); // course id
            $table->enum('type', ['percent','amount']);   // 'percent' => %, 'amount' => currency
            $table->decimal('value', 8, 2);               // 20 (means 20%) or 500 (Rs. 500)
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('max_uses')->nullable(); // optional usage cap
            $table->unsignedInteger('uses')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            // one discount per (student, course)
            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_discounts');
    }
};