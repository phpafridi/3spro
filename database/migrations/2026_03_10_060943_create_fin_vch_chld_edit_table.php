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
        Schema::create('fin_vch_chld_edit', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('request_id');
            $table->integer('chld_vch_id');
            $table->integer('mas_vch_id');
            $table->integer('GSL_code');
            $table->integer('SNO')->nullable();
            $table->integer('VoucherNo')->nullable();
            $table->string('RefNo', 50)->nullable();
            $table->integer('Department')->nullable();
            $table->integer('GSL')->nullable();
            $table->string('Description', 510)->nullable();
            $table->double('Credit')->nullable();
            $table->double('Debit')->nullable();
            $table->string('DM_TradeIn', 50)->nullable();
            $table->string('DM_No', 50)->nullable();
            $table->bigInteger('TradeIN_info')->nullable();
            $table->string('PBO_No', 50)->nullable();
            $table->integer('Investor')->nullable();
            $table->integer('Variant')->nullable();
            $table->integer('ModeOfPayment')->nullable();
            $table->integer('Activity')->nullable();
            $table->integer('mType')->default(1);
            $table->integer('Unit')->nullable();
            $table->integer('Region');
            $table->string('vchr_type', 15);
            $table->string('user', 25);
            $table->dateTime('submittime')->useCurrent();
            $table->timestamps();

            $table->index('mas_vch_id');
            $table->index('GSL_code');
            $table->index('request_id');
            $table->index('vchr_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_vch_chld_edit');
    }
};
