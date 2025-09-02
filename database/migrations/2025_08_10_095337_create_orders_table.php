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
        Schema::create('orders', function (Blueprint $table) {
          $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('status')->default('pending'); // pending, paid, failed, canceled
        $table->string('currency', 10)->default(env('APP_CURRENCY','USD'));
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('total', 10, 2)->default(0);
        $table->string('gateway')->nullable(); // stripe, paypal, jazzcash, easypaisa
        $table->string('gateway_ref')->nullable(); // transaction id / intent id
        $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
        $table->json('meta')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};