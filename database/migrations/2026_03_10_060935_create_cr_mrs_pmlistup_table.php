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
        Schema::create('cr_mrs_pmlistup', function (Blueprint $table) {
            $table->integer('listup_id', true);
            $table->string('source', 16);
            $table->date('listupdate');
            $table->date('exp_due_date');
            $table->integer('veh_id');
            $table->integer('cust_id');
            $table->integer('own_veh');
            $table->string('assign_to', 25);
            $table->date('assign_date');
            $table->integer('cur_mileage');
            $table->integer('relistup_status');
            $table->string('status', 36);
            $table->string('app_status', 45);
            $table->date('action_taken');
            $table->string('Remarks_RO', 28);
            $table->string('formula', 15);
            $table->dateTime('runed_on');
            $table->timestamps();

            $table->index('veh_id');
            $table->index('cust_id');
            $table->index('assign_to');
            $table->index('exp_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_mrs_pmlistup');
    }
};
