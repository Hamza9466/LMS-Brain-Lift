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
        Schema::create('coupons', function (Blueprint $table) {
               $table->id();
        $table->string('code')->unique();
        $table->enum('type', ['percent','fixed'])->default('percent');
        $table->decimal('value', 10, 2); // percent like 10.00 or fixed like 500.00
        $table->decimal('min_amount', 10, 2)->default(0);
        $table->unsignedInteger('max_uses')->nullable();
        $table->unsignedInteger('used_count')->default(0);
        $table->timestamp('starts_at')->nullable();
        $table->timestamp('ends_at')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};