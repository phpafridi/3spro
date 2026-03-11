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
        Schema::create('p_purch_inv', function (Blueprint $table) {
            $table->integer('Invoice_no', true);
            $table->string('jobber', 45);
            $table->string('Invoice_number', 20);
            $table->string('payment_method', 25);
            $table->integer('Purchase_Requis');
            $table->integer('Total_amount');
            $table->string('user', 30);
            $table->date('mdate');
            $table->dateTime('date');
            $table->string('deleverynote', 30);
            $table->string('consignmentnote', 30);
            $table->string('Receivername', 30);
            $table->integer('status');
            $table->timestamps();

            $table->index('jobber');
            $table->index('Invoice_number');
            $table->index('status');
            $table->index('mdate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_purch_inv');
    }
};
