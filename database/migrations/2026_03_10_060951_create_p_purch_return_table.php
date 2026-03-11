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
        Schema::create('p_purch_return', function (Blueprint $table) {
            $table->integer('p_return_id', true);
            $table->integer('PRJV');
            $table->integer('invoice_no');
            $table->integer('stock_id');
            $table->integer('unit_price');
            $table->integer('return_qty');
            $table->string('return_by', 35);
            $table->text('reason');
            $table->string('user', 30);
            $table->dateTime('datetime');
            $table->timestamps();

            $table->index('invoice_no');
            $table->index('stock_id');
            $table->index('PRJV');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_purch_return');
    }
};
