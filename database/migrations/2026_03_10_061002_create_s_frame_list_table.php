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
        Schema::create('s_frame_list', function (Blueprint $table) {
            $table->integer('f_id', true);
            $table->date('uploaded_date');
            $table->integer('full_frame', false, true)->length(45);
            $table->string('variant', 38);
            $table->string('fram_exactracted', 40);
            $table->integer('f_veh_id');
            $table->integer('f_cust_id');
            $table->date('last_visit');
            $table->string('Assignto', 25);
            $table->dateTime('Assigntime');
            $table->dateTime('Actiontaken');
            $table->string('status', 18);
            $table->timestamps();

            $table->index('f_veh_id');
            $table->index('f_cust_id');
            $table->index('Assignto');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_frame_list');
    }
};
