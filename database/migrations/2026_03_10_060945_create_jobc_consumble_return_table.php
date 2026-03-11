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
        Schema::create('jobc_consumble_return', function (Blueprint $table) {
            $table->integer('return_id', true);
            $table->integer('sale_id');
            $table->integer('stock_id');
            $table->integer('return_qty');
            $table->integer('return_amount');
            $table->dateTime('datetime');
            $table->string('user', 15);
            $table->timestamps();

            $table->index('sale_id');
            $table->index('stock_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_consumble_return');
    }
};
