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
        Schema::create('customer_data_del', function (Blueprint $table) {
            $table->integer('del_cust_id', true);
            $table->integer('Customer_id');
            $table->string('old_id', 8);
            $table->integer('Vehicle_id');
            $table->string('cust_type', 20);
            $table->string('Customer_name', 40);
            $table->string('off_phone', 25);
            $table->string('mobile', 25);
            $table->text('Address');
            $table->date('DOB');
            $table->string('City', 18);
            $table->string('Region', 18);
            $table->string('email', 55);
            $table->dateTime('Update_date');
            $table->string('CNIC', 25);
            $table->string('contact_type', 20);
            $table->string('NTN', 18);
            $table->string('STRN', 18);
            $table->string('Supplier', 18);
            $table->string('user', 30);
            $table->timestamps();

            $table->index('Vehicle_id');
            $table->index('Customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_data_del');
    }
};
