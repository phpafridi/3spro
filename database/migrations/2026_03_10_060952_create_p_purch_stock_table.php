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
        Schema::create('p_purch_stock', function (Blueprint $table) {
            $table->integer('stock_id', true);
            $table->date('date');
            $table->integer('Invoice_no');
            $table->string('part_no', 40);
            $table->string('Description', 55);
            $table->string('unit', 25);
            $table->integer('quantity');
            $table->integer('remain_qty');
            $table->decimal('Price', 11, 3);
            $table->integer('discount');
            $table->integer('tax');
            $table->decimal('Netamount', 11, 3);
            $table->string('cate_type', 25);
            $table->string('location', 55);
            $table->integer('purch_return');
            $table->string('Model', 40);
            $table->timestamps();

            $table->index('Invoice_no');
            $table->index('part_no');
            $table->index('remain_qty');
            $table->index('cate_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_purch_stock');
    }
};
