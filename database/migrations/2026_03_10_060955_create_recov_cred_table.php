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
        Schema::create('recov_cred', function (Blueprint $table) {
            $table->integer('cred_id', true);
            $table->string('cust_name', 55);
            $table->string('dm_invoice', 15);
            $table->string('Payment_method', 20);
            $table->string('RT_no', 15);
            $table->date('cr_date');
            $table->string('cr_amount', 15);
            $table->text('remarks');
            $table->string('user', 25);
            $table->dateTime('entytime');
            $table->timestamps();

            $table->index('cust_name');
            $table->index('dm_invoice');
            $table->index('cr_date');
            $table->index('RT_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recov_cred');
    }
};
