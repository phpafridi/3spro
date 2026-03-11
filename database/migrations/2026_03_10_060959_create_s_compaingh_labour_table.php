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
        Schema::create('s_compaingh_labour', function (Blueprint $table) {
            $table->integer('cl_id', true);
            $table->integer('compaingh_id');
            $table->string('labour_des', 255);
            $table->integer('labour_cost');
            $table->timestamps();

            $table->index('compaingh_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_compaingh_labour');
    }
};
