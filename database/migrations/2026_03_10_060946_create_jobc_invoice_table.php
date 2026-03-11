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
        Schema::create('jobc_invoice', function (Blueprint $table) {
            $table->integer('Invoice_id', true);
            $table->integer('Jobc_id')->unique();
            $table->integer('Labor');
            $table->integer('Parts');
            $table->integer('Sublet');
            $table->integer('Consumble');
            $table->integer('Ltax');
            $table->integer('Ptax');
            $table->integer('Stax');
            $table->integer('Ctax');
            $table->integer('Ldiscount');
            $table->integer('Pdiscount');
            $table->integer('Sdiscount');
            $table->integer('Cdiscount');
            $table->integer('Lnet');
            $table->integer('Pnet');
            $table->integer('Snet');
            $table->integer('Cnet');
            $table->integer('Total');
            $table->string('type', 10);
            $table->string('careof', 30);
            $table->string('cashier', 30);
            $table->dateTime('datetime');
            $table->string('Rec_status', 20);
            $table->timestamps();

            $table->index('Jobc_id');
            $table->index('datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_invoice');
    }
};
