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
        Schema::create('recov_debt', function (Blueprint $table) {
            $table->integer('cust_id', true);
            $table->string('cust_name', 50);
            $table->string('contact', 15);
            $table->string('Vehicle_name', 25);
            $table->string('Registration', 25);
            $table->integer('Invoice_no');
            $table->date('Db_date');
            $table->integer('Debt_amount');
            $table->text('Remarks');
            $table->string('user', 25);
            $table->dateTime('entytime');
            $table->timestamps();

            $table->index('cust_name');
            $table->index('Registration');
            $table->index('Invoice_no');
            $table->index('Db_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recov_debt');
    }
};
