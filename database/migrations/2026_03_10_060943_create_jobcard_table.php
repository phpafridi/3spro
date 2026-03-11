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
        Schema::create('jobcard', function (Blueprint $table) {
            $table->integer('Jobc_id', true);
            $table->string('Customer_name', 40);
            $table->string('Veh_reg_no', 20);
            $table->integer('Vehicle_id');
            $table->integer('Customer_id');
            $table->dateTime('Open_date_time');
            $table->string('comp_appointed', 35);
            $table->string('cust_source', 35);
            $table->string('MSI_cat', 25);
            $table->string('serv_nature', 15);
            $table->string('RO_type', 25);
            $table->string('Fuel', 20);
            $table->integer('cust_waiting');
            $table->integer('Mileage');
            $table->text('VOC');
            $table->dateTime('Estim_time');
            $table->string('Estim_cost', 10);
            $table->string('SA', 35);
            $table->string('Diagnose_by', 30);
            $table->integer('status');
            $table->dateTime('closing_time');
            $table->integer('rating_done');
            $table->integer('PSFU');
            $table->integer('PM_status');
            $table->integer('RO_no');
            $table->timestamps();

            $table->index('status');
            $table->index('Customer_id');
            $table->index('Vehicle_id');
            $table->index('RO_no');
            $table->index('SA');
            $table->index('Open_date_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobcard');
    }
};
