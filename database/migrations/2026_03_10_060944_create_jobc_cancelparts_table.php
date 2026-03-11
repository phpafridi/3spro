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
        Schema::create('jobc_cancelparts', function (Blueprint $table) {
            $table->integer('cancel_id', true);
            $table->string('sa_shey', 14);
            $table->string('part_no', 34);
            $table->integer('stock_id');
            $table->integer('qty');
            $table->integer('amount');
            $table->string('issue_by', 15);
            $table->string('cancel_by', 15);
            $table->dateTime('issue_time');
            $table->dateTime('cancel_time');
            $table->integer('RO');
            $table->timestamps();

            $table->index('RO');
            $table->index('part_no');
            $table->index('stock_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_cancelparts');
    }
};
