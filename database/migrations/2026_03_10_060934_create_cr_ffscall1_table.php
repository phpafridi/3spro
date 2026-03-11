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
        Schema::create('cr_ffscall1', function (Blueprint $table) {
            $table->integer('ffscall1_id', true);
            $table->string('delv_id', 15);
            $table->string('call_status', 20);
            $table->string('veh_sold', 25);
            $table->integer('current_mileage');
            $table->string('ffs_done', 35);
            $table->string('appointment', 12);
            $table->text('reason');
            $table->string('username', 30);
            $table->dateTime('calldatentime');
            $table->string('new_purchaseer', 40);
            $table->string('new_contact', 15);
            $table->timestamps();

            $table->index('delv_id');
            $table->index('username');
            $table->index('calldatentime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_ffscall1');
    }
};
