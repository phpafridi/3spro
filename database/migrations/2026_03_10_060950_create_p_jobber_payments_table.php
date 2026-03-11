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
        Schema::create('p_jobber_payments', function (Blueprint $table) {
            $table->integer('payment_id', true);
            $table->string('jobber', 35);
            $table->string('trans_type', 25);
            $table->integer('amount');
            $table->string('payment_method', 25);
            $table->string('rec_paid_by', 30);
            $table->text('remarks');
            $table->string('user', 30);
            $table->dateTime('datetime');
            $table->timestamps();

            $table->index('jobber');
            $table->index('trans_type');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_jobber_payments');
    }
};
