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
        Schema::create('customer_ratings', function (Blueprint $table) {
            $table->string('RO', 20)->primary();
            $table->dateTime('datetime');
            $table->integer('Management', false, true)->length(1);
            $table->integer('Services', false, true)->length(1);
            $table->integer('prices', false, true)->length(1);
            $table->integer('cleanance', false, true)->length(1);
            $table->integer('behaviour', false, true)->length(1);
            $table->integer('professionalism', false, true)->length(1);
            $table->integer('Tech_expertize', false, true)->length(1);
            $table->string('commenty_type', 15);
            $table->text('message');
            $table->string('SA', 35);
            $table->timestamps();

            $table->index('SA');
            $table->index('datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_ratings');
    }
};
