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
            $table->integer('id', true);
            $table->string('login_id', 35)->unique();
            $table->string('Name', 34)->unique();
            $table->string('password', 33);
            $table->string('email', 34);
            $table->dateTime('last_login');
            $table->string('mobile', 23);
            $table->string('dept', 34);
            $table->string('position', 35);
            $table->string('image',255);
            $table->dateTime('last_logout');
            $table->timestamps();

            $table->index('login_id');
            $table->index('Name');
            $table->index('dept');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
