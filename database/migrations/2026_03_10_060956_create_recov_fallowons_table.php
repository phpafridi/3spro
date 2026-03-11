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
        Schema::create('recov_fallowons', function (Blueprint $table) {
            $table->integer('id', true)->length(8);
            $table->string('cust_name', 42);
            $table->date('Datetime');
            $table->string('Person_contacted', 35);
            $table->string('Contact_type', 25);
            $table->text('Remarks');
            $table->timestamps();

            $table->index('cust_name');
            $table->index('Datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recov_fallowons');
    }
};
