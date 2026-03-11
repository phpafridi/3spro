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
        Schema::create('cr_psfu', function (Blueprint $table) {
            $table->integer('psfu_id', true);
            $table->integer('RO');
            $table->string('call_status', 12);
            $table->string('q1', 12);
            $table->string('q11', 20);
            $table->string('q12', 12);
            $table->date('q13');
            $table->string('q2', 12);
            $table->string('q21', 35);
            $table->string('q3', 12);
            $table->string('q31', 35);
            $table->string('q4', 12);
            $table->string('q41', 36);
            $table->text('Remarks');
            $table->dateTime('Datetime');
            $table->string('CRO', 20);
            $table->timestamps();

            $table->index('RO');
            $table->index('CRO');
            $table->index('call_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_psfu');
    }
};
