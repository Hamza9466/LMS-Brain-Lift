<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Add first_name / last_name if missing
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name', 50)->after('id');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name', 50)->after('first_name');
            }
        });

        // Normalize existing role data to the new set
        DB::table('users')->whereNull('role')->update(['role' => 'student']);
        DB::table('users')->where('role', '')->update(['role' => 'student']);
        DB::table('users')->where('role', 'user')->update(['role' => 'student']);
        DB::table('users')->whereNotIn('role', ['admin','student'])->update(['role' => 'student']);

        // Convert role to ENUM('admin','student') with default 'student' (MySQL/MariaDB)
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','student') NOT NULL DEFAULT 'student'");

        // Ensure email is unique (adds index only if missing)
        $hasUnique = collect(DB::select("SHOW INDEX FROM `users` WHERE Column_name = 'email'"))
            ->contains(function ($i) { return (int)($i->Non_unique ?? 1) === 0; });

        if (! $hasUnique) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('email');
            });
        }
    }

    public function down(): void
    {
        // Revert role back to VARCHAR to keep rollback simple
        DB::statement("ALTER TABLE `users` MODIFY `role` VARCHAR(20) NULL");

        // Drop added columns if they exist
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('users', 'first_name')) {
                $table->dropColumn('first_name');
            }
        });

        // (Optional) remove unique index on email:
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropUnique('users_email_unique');
        // });
    }
};