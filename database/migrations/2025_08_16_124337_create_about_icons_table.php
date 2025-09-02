<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // database/migrations/xxxx_xx_xx_create_about_icons_table.php
public function up()
{
    Schema::create('about_icons', function (Blueprint $table) {
        $table->id();
        $table->string('icon')->nullable(); // will store the image path
        $table->string('digits')->nullable();
        $table->text('shortdescription')->nullable();
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('about_icons');
    }
};
