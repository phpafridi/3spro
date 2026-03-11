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
        Schema::create('tsure_service', function (Blueprint $table) {
            $table->integer('tsure_id', true);
            $table->integer('veh_id');
            $table->integer('cust_id');
            $table->integer('demand_price');
            $table->date('Next_followup');
            $table->string('RNP', 35);
            $table->string('q_status', 20);
            $table->string('user', 25);
            $table->dateTime('datetime');
            $table->string('SA', 30);
            $table->timestamps();

            $table->index('veh_id');
            $table->index('cust_id');
            $table->index('Next_followup');
            $table->index('q_status');
            $table->index('SA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tsure_service');
    }
};
