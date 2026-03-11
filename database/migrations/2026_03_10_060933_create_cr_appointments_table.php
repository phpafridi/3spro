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
        Schema::create('cr_appointments', function (Blueprint $table) {
            $table->integer('app_id', true);
            $table->string('CustomerName', 40);
            $table->string('Mobile', 35);
            $table->string('job_nature', 15);
            $table->integer('cust_id');
            $table->integer('veh_id');
            $table->string('source', 25);
            $table->integer('source_id');
            $table->string('veh_rec', 25);
            $table->string('veh_details', 35);
            $table->integer('ro_no', false, true)->length(6);
            $table->text('VOC');
            $table->string('bay', 30);
            $table->text('labor');
            $table->text('parts');
            $table->integer('Labor_cost');
            $table->integer('Parts_cost');
            $table->integer('parts_status');
            $table->string('parts_user', 25);
            $table->dateTime('parts_datatime');
            $table->dateTime('appt_datetime');
            $table->dateTime('Deliverytime');
            $table->string('mature', 5);
            $table->dateTime('mature_datetime');
            $table->string('SA', 15);
            $table->string('CRO', 25);
            $table->dateTime('appt_entry_datetime');
            $table->string('Variant', 35);
            $table->string('app_status', 15);
            $table->text('remarks');
            $table->string('reschedule_by', 20);
            $table->timestamps();

            $table->index('cust_id');
            $table->index('veh_id');
            $table->index('Mobile');
            $table->index('ro_no');
            $table->index('appt_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_appointments');
    }
};
