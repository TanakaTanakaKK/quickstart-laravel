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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('archive_image_path')->unique();
            $table->string('thumbnail_image_path')->unique();
            $table->integer('gender');
            $table->string('kana_name');
            $table->string('nickname');
            $table->date('birthday');
            $table->string('phone_number')->unique();
            $table->string('postal_code');
            $table->integer('prefecture');
            $table->string('address');
            $table->string('block');
            $table->string('building')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};