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
        Schema::table('lesson_user', function (Blueprint $table) {
                  $table->decimal('progress_percent', 5, 2)->default(0)->after('user_id');
        $table->timestamp('watched_at')->nullable()->after('progress_percent');
        $table->timestamp('quiz_passed_at')->nullable()->after('watched_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_user', function (Blueprint $table) {
                    $table->dropColumn(['progress_percent', 'watched_at', 'quiz_passed_at']);

        });
    }
};