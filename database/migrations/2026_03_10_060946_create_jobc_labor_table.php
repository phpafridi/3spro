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
        Schema::create('jobc_labor', function (Blueprint $table) {
            $table->integer('Labor_id', true);
            $table->integer('RO_no');
            $table->text('Labor');
            $table->string('type', 30);
            $table->integer('cost');
            $table->integer('Additional');
            $table->string('reason', 20);
            $table->dateTime('estimated_time');
            $table->string('status', 25);
            $table->dateTime('entry_time');
            $table->dateTime('Assign_time');
            $table->dateTime('end_time');
            $table->string('team', 30);
            $table->string('bay', 30);
            $table->text('remarks');
            $table->string('resumetime', 30);
            $table->string('jc', 21);
            $table->timestamps();

            $table->index('RO_no');
            $table->index('status');
            $table->index('team');
            $table->index('bay');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_labor');
    }
};
