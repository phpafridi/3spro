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
        Schema::create('p_sale_part', function (Blueprint $table) {
            $table->integer('sell_id', true);
            $table->integer('sale_inv');
            $table->integer('stock_id');
            $table->string('part_no', 23);
            $table->string('Description', 55);
            $table->integer('quantity', false, true)->length(6);
            $table->integer('sale_price', false, true)->length(6);
            $table->integer('discount');
            $table->integer('tax');
            $table->integer('netamount', false, true)->length(6);
            $table->integer('SRJV_return');
            $table->integer('remain_qty');
            $table->timestamps();

            $table->index('sale_inv');
            $table->index('stock_id');
            $table->index('part_no');
            $table->index('sell_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_sale_part');
    }
};
