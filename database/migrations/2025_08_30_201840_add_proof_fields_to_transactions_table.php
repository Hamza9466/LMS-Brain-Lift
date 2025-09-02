<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('proof_path')->nullable()->after('reference');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('proof_path');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('review_note')->nullable()->after('reviewed_at');
            // you'll use status: pending -> submitted -> captured or rejected
        });
    }
    public function down(): void {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['proof_path','reviewed_by','reviewed_at','review_note']);
        });
    }
};