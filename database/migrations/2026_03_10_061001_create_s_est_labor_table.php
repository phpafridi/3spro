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
        Schema::create('s_est_labor', function (Blueprint $table) {
            $table->integer('est_lab_id', true);
            $table->integer('estm_id');
            $table->string('Labor', 45);
            $table->integer('cost');
            $table->timestamps();

            $table->index('estm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_est_labor');
    }
};
