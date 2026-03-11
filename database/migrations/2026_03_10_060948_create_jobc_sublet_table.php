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
        Schema::create('jobc_sublet', function (Blueprint $table) {
            $table->integer('sublet_id', true);
            $table->integer('RO_no');
            $table->text('Sublet');
            $table->string('type', 30);
            $table->integer('qty');
            $table->integer('unitprice');
            $table->integer('total');
            $table->dateTime('entry_datetime');
            $table->integer('additional');
            $table->string('status', 35);
            $table->string('jc', 45);
            $table->dateTime('end_time');
            $table->dateTime('Asign_time');
            $table->text('parts_details');
            $table->string('Vendor', 40);
            $table->string('who_taking', 35);
            $table->integer('Vendor_price');
            $table->integer('logistics');
            $table->timestamps();

            $table->index('RO_no');
            $table->index('status');
            $table->index('Vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_sublet');
    }
};
