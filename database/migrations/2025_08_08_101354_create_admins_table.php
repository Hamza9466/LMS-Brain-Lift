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
        Schema::create('admin_details', function (Blueprint $table) {
               $table->id();
        $table->unsignedBigInteger('user_id')->unique();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('phone')->nullable();
        $table->string('department')->nullable();
                $table->string('profile_image')->nullable();

        $table->timestamps();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};