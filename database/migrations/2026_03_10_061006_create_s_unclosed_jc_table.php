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
        Schema::create('s_unclosed_jc', function (Blueprint $table) {
            $table->integer('unjc_Id', true);
            $table->integer('jobc_id');
            $table->integer('total_invoice');
            $table->dateTime('old_inv_datime');
            $table->text('SM_reason');
            $table->string('SM', 25);
            $table->dateTime('sm_datetime');
            $table->text('fin_reason');
            $table->dateTime('fin_datetime');
            $table->string('fin_guy', 25);
            $table->integer('status');
            $table->timestamps();

            $table->index('jobc_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_unclosed_jc');
    }
};
