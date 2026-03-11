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
        Schema::create('p_stock_bkup', function (Blueprint $table) {
            $table->integer('stock_id');
            $table->string('part_no', 45);
            $table->date('bkup_date');
            $table->integer('remainging_stock');
            $table->timestamps();

            $table->index('stock_id');
            $table->index('part_no');
            $table->index('bkup_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_stock_bkup');
    }
};
