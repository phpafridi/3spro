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
        Schema::create('jobc_consumble', function (Blueprint $table) {
            $table->integer('cons_sale_id', true);
            $table->integer('cons_req_no');
            $table->integer('RO_no');
            $table->string('cons_number', 40);
            $table->string('cons_description', 55);
            $table->dateTime('entry_datetime');
            $table->integer('qty');
            $table->integer('req_qty');
            $table->integer('issued_qty');
            $table->integer('Stock_id');
            $table->integer('unitprice');
            $table->integer('total');
            $table->string('issue_to', 35);
            $table->dateTime('issue_time');
            $table->integer('Additional');
            $table->integer('status');
            $table->string('issue_by', 40);
            $table->integer('p_return');
            $table->integer('incentive_status');
            $table->timestamps();

            $table->index('RO_no');
            $table->index('Stock_id');
            $table->index('cons_number');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_consumble');
    }
};
