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
        Schema::create('s_labor_request', function (Blueprint $table) {
            $table->integer('req_id', true);
            $table->string('labor', 40)->unique();
            $table->integer('cate1');
            $table->integer('cate2');
            $table->integer('cate3');
            $table->integer('cate4');
            $table->integer('cate5');
            $table->text('remarks');
            $table->string('status', 11);
            $table->string('who_req', 27);
            $table->dateTime('when_req');
            $table->string('who_acept', 27);
            $table->dateTime('when_acept');
            $table->timestamps();

            $table->index('labor');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_labor_request');
    }
};
