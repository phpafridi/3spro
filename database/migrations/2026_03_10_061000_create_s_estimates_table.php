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
        Schema::create('s_estimates', function (Blueprint $table) {
            $table->integer('est_id', true);
            $table->integer('cust_id');
            $table->integer('veh_id');
            $table->string('estimate_type', 20);
            $table->string('payment_mode', 28);
            $table->string('cust_type', 35);
            $table->string('insur_company', 54);
            $table->string('surv_name', 43);
            $table->string('surv_type', 23);
            $table->dateTime('est_delivery');
            $table->string('user', 25);
            $table->dateTime('entry_datetime');
            $table->integer('est_status');
            $table->string('sur_cont', 25);
            $table->timestamps();

            $table->index('cust_id');
            $table->index('veh_id');
            $table->index('est_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_estimates');
    }
};
