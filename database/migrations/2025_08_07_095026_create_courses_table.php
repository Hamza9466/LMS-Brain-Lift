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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description');
    $table->string('thumbnail')->nullable();
    $table->string('level')->default('Beginner');
    $table->decimal('price', 8, 2)->nullable();
    $table->boolean('is_free')->default(false);
      $table->decimal('discount_percentage', 5, 2)->nullable();
        $table->decimal('discount_price', 10, 2)->nullable();
    $table->enum('status', ['draft', 'published'])->default('draft');
        $table->unsignedBigInteger('teacher_id')->nullable();
    $table->unsignedBigInteger('created_by');

    $table->timestamps();

    // Foreign keys
    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null'); // ✅ this was missing





       $table->string('short_description')->nullable();
            $table->string('subject')->nullable();
            $table->boolean('is_featured')->default(false);

            // Pricing
            $table->decimal('compare_at_price', 8, 2)->nullable();

            // Content
            $table->longText('long_description')->nullable();
            $table->json('what_you_will_learn')->nullable();
            $table->json('requirements')->nullable();
            $table->json('who_is_for')->nullable();
            $table->json('tags')->nullable();

            // Media
            $table->string('promo_video_url')->nullable();
            $table->json('materials')->nullable();

            // Meta / stats
            $table->unsignedInteger('total_lessons')->nullable();
            $table->unsignedInteger('total_duration_minutes')->nullable();
            $table->string('language')->default('English');

            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->json('rating_breakdown')->nullable();
            $table->unsignedInteger('enrollment_count')->default(0);

            // Publishing
            $table->timestamp('published_at')->nullable();

            // Helpful indexes
            $table->index('status');
            $table->index('published_at');
            $table->index('teacher_id');
            $table->index('subject');
            $table->index('language');
    
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};