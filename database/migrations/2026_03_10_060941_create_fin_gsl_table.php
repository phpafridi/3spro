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
        Schema::create('fin_gsl', function (Blueprint $table) {
            $table->integer('GSL_ID', true);
            $table->integer('GL_id');
            $table->integer('GSL_code');
            $table->string('GSL_name', 45);
            $table->text('Description');
            $table->string('gsl_user', 35);
            $table->dateTime('gsl_datetime');
            $table->string('gsl_status', 25);
            $table->integer('GLCode');
            $table->integer('GSLCode');
            $table->string('Name', 70)->nullable();
            $table->bigInteger('Type')->nullable();
            $table->bigInteger('Activity')->nullable();
            $table->timestamps();

            $table->index('GL_id');
            $table->index('GSL_code');
            $table->index('GLCode');
            $table->index('GSLCode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_gsl');
    }
};
