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
        Schema::create('jobc_checklist', function (Blueprint $table) {
            $table->integer('RO_id')->primary();
            $table->integer('usb', false, true)->length(5);
            $table->integer('cardreader', false, true)->length(5);
            $table->integer('ashtray', false, true)->length(5);
            $table->integer('lighter', false, true)->length(5);
            $table->integer('wiperblader', false, true)->length(5);
            $table->integer('seatcover', false, true)->length(5);
            $table->integer('dickymat', false, true)->length(5);
            $table->integer('sparewheel', false, true)->length(5);
            $table->integer('jackhandle', false, true)->length(5);
            $table->integer('tools', false, true)->length(5);
            $table->integer('perfume', false, true)->length(5);
            $table->integer('remote', false, true)->length(5);
            $table->integer('floormate', false, true)->length(5);
            $table->integer('mirror', false, true)->length(5);
            $table->integer('cassete', false, true)->length(5);
            $table->integer('hubcaps', false, true)->length(5);
            $table->integer('wheelcaps', false, true)->length(5);
            $table->integer('monogram', false, true)->length(5);
            $table->integer('extrakeys', false, true)->length(5);
            $table->integer('anttena', false, true)->length(5);
            $table->integer('clock', false, true)->length(5);
            $table->integer('Navigation', false, true)->length(5);
            $table->timestamps();

            $table->index('RO_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobc_checklist');
    }
};
