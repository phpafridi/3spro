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
        Schema::create('p_tech_incentive', function (Blueprint $table) {
            $table->integer('inc_id', true);
            $table->string('wp_or_wc', 5);
            $table->integer('wp_wc_id');
            $table->integer('amount');
            $table->string('user', 16);
            $table->dateTime('datetime');
            $table->timestamps();

            $table->index('wp_or_wc');
            $table->index('wp_wc_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_tech_incentive');
    }
};
