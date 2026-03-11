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
        Schema::create('cr_pmcall1', function (Blueprint $table) {
            $table->integer('PMcall1_id', true);
            $table->integer('veh_id');
            $table->string('call_status', 20);
            $table->string('veh_sold', 25);
            $table->integer('current_mileage');
            $table->string('PM_done', 35);
            $table->string('appointment', 9);
            $table->text('reason');
            $table->string('username', 30);
            $table->dateTime('calldatentime');
            $table->timestamps();

            $table->index('veh_id');
            $table->index('username');
            $table->index('calldatentime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_pmcall1');
    }
};
