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
        Schema::create('uio', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('UIO_Year');
            $table->integer('UIO');
            $table->string('user', 25);
            $table->dateTime('datentime');
            $table->timestamps();

            $table->index('UIO_Year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uio');
    }
};
