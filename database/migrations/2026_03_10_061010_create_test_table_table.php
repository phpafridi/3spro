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
        Schema::create('test_table', function (Blueprint $table) {
            $table->string('name', 55);
            $table->string('1', 34);
            $table->string('2', 34);
            $table->string('3', 34);
            $table->string('4', 34);
            $table->string('5', 34);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_table');
    }
};
