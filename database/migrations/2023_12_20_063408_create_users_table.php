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
            $table->string('name')->nullable(false);
            $table->string('email')->unique();
            $table->string('gender')->nullable(false);
            $table->string('kana_name')->nullable(false);
            $table->string('nickname')->nullable(false);
            $table->date('birthday')->nullable(false);
            $table->string('phone_number')->unique();
            $table->string('img_path')->nullable();
            $table->string('postal_code');
            $table->string('prefecture');
            $table->string('city');
            $table->string('town');
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
