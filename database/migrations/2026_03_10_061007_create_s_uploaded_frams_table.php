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
        Schema::create('s_uploaded_frams', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('uploaded_id');
            $table->string('VIN', 38);
            $table->string('full_VIN', 40);
            $table->string('secondary_info', 40);
            $table->integer('veh_id');
            $table->integer('cust_id');
            $table->date('uploaded_date');
            $table->string('Assign_to', 20);
            $table->date('Assign_date');
            $table->date('Action_taken');
            $table->integer('status');
            $table->timestamps();

            $table->index('veh_id');
            $table->index('cust_id');
            $table->index('Assign_to');
            $table->index('status');
            $table->index('uploaded_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_uploaded_frams');
    }
};
