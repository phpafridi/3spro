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
        Schema::create('cr_problem_tray', function (Blueprint $table) {
            $table->integer('p_id', true);
            $table->string('source_by', 15);
            $table->integer('source_id');
            $table->integer('cust_id');
            $table->integer('veh_id');
            $table->string('problem', 20);
            $table->string('customer_name', 50);
            $table->string('Contact', 45);
            $table->text('remarks');
            $table->string('cro', 20);
            $table->dateTime('fdatetime');
            $table->integer('status');
            $table->text('prev_data');
            $table->text('updated_data');
            $table->string('who_did', 25);
            $table->dateTime('when_did');
            $table->text('ActionTaken');
            $table->text('ActionCompleted');
            $table->text('Completed');
            $table->text('messageforsa');
            $table->timestamps();

            $table->index('cust_id');
            $table->index('veh_id');
            $table->index('cro');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_problem_tray');
    }
};
