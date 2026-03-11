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
        Schema::create('s_warranty', function (Blueprint $table) {
            $table->integer('w_id', true);
            $table->integer('jobc_id');
            $table->string('wc_no', 15);
            $table->string('status', 15);
            $table->date('claim_date');
            $table->date('approve_date');
            $table->string('user', 30);
            $table->text('remarks');
            $table->date('delivery_date');
            $table->integer('FFS');
            $table->timestamps();

            $table->index('jobc_id');
            $table->index('wc_no');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_warranty');
    }
};
