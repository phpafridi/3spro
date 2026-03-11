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
        Schema::create('p_parts_subcat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('category', 35);
            $table->string('subcategory', 36);
            $table->string('partnumber', 50);
            $table->timestamps();

            $table->index('category');
            $table->index('subcategory');
            $table->index('partnumber');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_parts_subcat');
    }
};
