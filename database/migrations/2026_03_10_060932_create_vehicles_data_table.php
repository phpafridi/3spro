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
        Schema::create('vehicles_data', function (Blueprint $table) {
            $table->integer('Vehicle_id', true);
            $table->integer('cust_id');
            $table->integer('Customer_id');
            $table->string('Registration', 25);
            $table->string('Frame_no', 25)->unique();
            $table->string('Model', 32);
            $table->string('Variant', 32);
            $table->string('Colour', 25);
            $table->string('Make', 25);
            $table->string('Engine_Code', 25);
            $table->string('Engine_number', 35);
            $table->string('Wrnty_book_no', 21);
            $table->string('Insurance', 20);
            $table->dateTime('Update_date');
            $table->string('into_sell', 10);
            $table->string('model_year', 10);
            $table->string('demand_price', 35);
            $table->string('user', 25);
            $table->string('own_vehicle', 4);
            $table->string('updated_by', 30);
            $table->string('v_status', 20);
            $table->timestamps();

            $table->index('cust_id');
            $table->index('Customer_id');
            $table->index('Registration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles_data');
    }
};
