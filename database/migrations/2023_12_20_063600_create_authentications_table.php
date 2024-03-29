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
        Schema::create('authentications', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->string('email');
            $table->integer('status');
            $table->timestamp('expired_at');
            $table->integer('type');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentications');
    }
};
