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
        Schema::create('s_cust_veh', function (Blueprint $table) {
            $table->integer('iddd', true);
            $table->integer('cust_id');
            $table->integer('veh_id');
            $table->timestamps();

            $table->index('cust_id');
            $table->index('veh_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_cust_veh');
    }
};
