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
        Schema::create('jobc_parts', function (Blueprint $table) {
            $table->integer('parts_sale_id', true);
            $table->integer('part_invoice_no');
            $table->integer('RO_no');
            $table->string('part_number', 40);
            $table->text('part_description');
            $table->dateTime('entry_datetime');
            $table->integer('qty');
            $table->integer('req_qty');
            $table->integer('issued_qty');
            $table->integer('unitprice');
            $table->integer('total');
            $table->string('issue_to', 35);
            $table->dateTime('issue_time');
            $table->integer('Additional', false, true)->length(6);
            $table->integer('Stock_id');
            $table->integer('status')->comment('1 issue, 2 NA');
            $table->string('issue_by', 40);
            $table->integer('p_return');
            $table->integer('incentive_status');
            $table->timestamps();

            $table->index('RO_no');
            $table->index('part_number');
            $table->index('Stock_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_parts');
    }
};
