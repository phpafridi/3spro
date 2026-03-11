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
        Schema::create('s_est_parts', function (Blueprint $table) {
            $table->integer('estm_part_id', true);
            $table->integer('estm_id');
            $table->string('part_number', 40);
            $table->text('part_description');
            $table->dateTime('entry_datetime');
            $table->integer('qty');
            $table->integer('req_qty');
            $table->integer('unitprice');
            $table->integer('total');
            $table->string('issue_to', 35);
            $table->dateTime('issue_time');
            $table->integer('Stock_id');
            $table->integer('status')->comment('1 issue, 2 NA');
            $table->string('issue_by', 40);
            $table->string('type', 18);
            $table->timestamps();

            $table->index('estm_id');
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
        Schema::dropIfExists('s_est_parts');
    }
};
