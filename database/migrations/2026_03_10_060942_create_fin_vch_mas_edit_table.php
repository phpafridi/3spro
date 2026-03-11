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
        Schema::create('fin_vch_mas_edit', function (Blueprint $table) {
            $table->integer('request_id', true);
            $table->text('request_reason');
            $table->string('who_requested', 30);
            $table->dateTime('request_DD')->useCurrent();
            $table->integer('mas_vch_id');
            $table->integer('VoucherNo');
            $table->string('vchr_type', 20);
            $table->string('RefNo', 50);
            $table->dateTime('VoucherDate');
            $table->string('Payee', 150)->nullable();
            $table->string('BookNo', 50)->nullable();
            $table->string('UserName', 50)->nullable();
            $table->string('A_T', 25)->nullable();
            $table->string('Authenticate', 50)->nullable();
            $table->boolean('Cancel')->nullable();
            $table->dateTime('submiton')->useCurrent();
            $table->dateTime('complete_submition');
            $table->timestamps();

            $table->index('vchr_type');
            $table->index('RefNo');
            $table->index('VoucherDate');
            $table->index('mas_vch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_vch_mas_edit');
    }
};
