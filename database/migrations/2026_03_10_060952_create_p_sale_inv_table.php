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
        Schema::create('p_sale_inv', function (Blueprint $table) {
            $table->integer('sale_inv', true);
            $table->string('Jobber', 40);
            $table->string('payment_method', 15);
            $table->integer('discount');
            $table->integer('tax');
            $table->text('remarks');
            $table->integer('Total_amount');
            $table->string('user', 30);
            $table->dateTime('datetime');
            $table->integer('status');
            $table->timestamps();

            $table->index('Jobber');
            $table->index('payment_method');
            $table->index('status');
            $table->index('datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_sale_inv');
    }
};
