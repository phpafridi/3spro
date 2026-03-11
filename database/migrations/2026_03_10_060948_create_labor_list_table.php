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
        Schema::create('labor_list', function (Blueprint $table) {
            $table->integer('Labor_ID', true);
            $table->string('Labor', 60)->unique();
            $table->integer('Cate1');
            $table->integer('Cate2');
            $table->integer('Cate3');
            $table->integer('Cate4');
            $table->integer('Cate5');
            $table->timestamps();

            $table->index('Labor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor_list');
    }
};
