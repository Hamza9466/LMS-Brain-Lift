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
        Schema::create('student_details', function (Blueprint $table) {
               $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('first_name');
    $table->string('last_name');
    $table->string('username')->unique();
    $table->string('profile_image')->nullable();
    $table->string('phone')->nullable();
    $table->enum('gender', ['male', 'female', 'other'])->nullable();
    $table->date('dob')->nullable();
    $table->text('address')->nullable();
    $table->string('city')->nullable();
    $table->string('country')->nullable();
    $table->string('institute_name')->nullable();
    $table->string('program_name')->nullable();
    $table->string('enrollment_year')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_details');
    }
};