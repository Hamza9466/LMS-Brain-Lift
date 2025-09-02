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
        Schema::create('transactions', function (Blueprint $table) {
             $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        $table->string('gateway');
        $table->string('status'); // created, authorized, captured, failed
        $table->decimal('amount', 10, 2);
        $table->string('currency', 10);
        $table->string('reference')->nullable(); // gateway id
        $table->json('payload')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};