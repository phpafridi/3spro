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
        Schema::create('variant_codes', function (Blueprint $table) {
            $table->integer('variant_id', true);
            $table->string('Variant', 35);
            $table->string('Model', 25);
            $table->string('Make', 20);
            $table->string('Fram', 20);
            $table->string('Engine', 20);
            $table->string('Category', 11);
            $table->timestamps();

            $table->index('Variant');
            $table->index('Model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_codes');
    }
};
