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
        Schema::create('s_vin_check', function (Blueprint $table) {
            $table->integer('vin_id', true);
            $table->integer('jobcard');
            $table->integer('frameno');
            $table->integer('listid');
            $table->string('full_vin', 255);
            $table->integer('ActionTaken')->default(0);
            $table->timestamp('inserteddate')->useCurrent();
            $table->timestamp('updatedate')->useCurrent()->useCurrentOnUpdate();
            $table->string('doneondate', 255);
            $table->integer('veh_id');
            $table->string('cust_name', 255);
            $table->integer('cust_id');
            $table->string('veh_reg', 255);
            $table->timestamps();

            $table->index('jobcard');
            $table->index('veh_id');
            $table->index('cust_id');
            $table->index('frameno');
            $table->index('listid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_vin_check');
    }
};
