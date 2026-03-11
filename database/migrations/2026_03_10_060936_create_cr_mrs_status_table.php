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
        Schema::create('cr_mrs_status', function (Blueprint $table) {
            $table->integer('script_id', true);
            $table->dateTime('script_datetime');
            $table->date('fordate');
            $table->integer('NVD');
            $table->integer('FFS_sms');
            $table->integer('FFS_sms_s');
            $table->integer('FFS_call1');
            $table->integer('FFS_call1_s');
            $table->integer('FFS_call2');
            $table->integer('FFS_call2_s');
            $table->integer('PM_sms');
            $table->integer('PM_sms_s');
            $table->integer('PM_call1');
            $table->integer('PM_call1_s');
            $table->integer('PM_call2');
            $table->integer('PM_call2_s');
            $table->integer('Appointments');
            $table->integer('Appointments_s');
            $table->timestamps();

            $table->index('fordate');
            $table->index('script_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_mrs_status');
    }
};
