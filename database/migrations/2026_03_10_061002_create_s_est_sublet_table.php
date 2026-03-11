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
        Schema::create('s_est_sublet', function (Blueprint $table) {
            $table->integer('est_sub_id', true);
            $table->integer('estm_id');
            $table->string('Sublet', 45);
            $table->integer('unitprice');
            $table->integer('qty');
            $table->integer('total');
            $table->timestamps();

            $table->index('estm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_est_sublet');
    }
};
