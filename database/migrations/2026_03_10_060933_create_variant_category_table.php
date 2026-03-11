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
        Schema::create('variant_category', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('dsf', 70);
            $table->string('saf', 70);
            $table->string('saffd', 70);
            $table->string('sdaf', 70);
            $table->string('sfd', 70);
            $table->timestamps();

            $table->index('dsf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_category');
    }
};
