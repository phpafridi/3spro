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
        Schema::create('jobc_consumble_p', function (Blueprint $table) {
            $table->integer('workshop_id', true);
            $table->integer('parts_sale_id');
            $table->integer('stock_id');
            $table->string('part_no', 48);
            $table->integer('req_qty');
            $table->integer('qty_issued');
            $table->string('user', 25);
            $table->dateTime('date_time');
            $table->integer('issued_qty');
            $table->timestamps();

            $table->index('parts_sale_id');
            $table->index('stock_id');
            $table->index('part_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_consumble_p');
    }
};
