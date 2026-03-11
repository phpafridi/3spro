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
        Schema::create('s_bays', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('bay_name', 10);
            $table->string('bay_type', 30);
            $table->integer('status');
            $table->string('category', 25);
            $table->integer('selection');
            $table->timestamps();

            $table->index('bay_name');
            $table->index('status');
            $table->index('bay_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_bays');
    }
};
