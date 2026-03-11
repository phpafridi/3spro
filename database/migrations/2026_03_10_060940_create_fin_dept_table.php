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
        Schema::create('fin_dept', function (Blueprint $table) {
            $table->integer('dep_auto', true);
            $table->integer('Code');
            $table->string('Department', 50)->nullable();
            $table->timestamps();

            $table->index('Code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_dept');
    }
};
