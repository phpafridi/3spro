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
        Schema::create('cr_sms', function (Blueprint $table) {
            $table->integer('sms_id', true);
            $table->date('sentdate');
            $table->string('source', 20);
            $table->integer('source_id');
            $table->string('mobile', 35);
            $table->string('status', 15);
            $table->string('cro', 25);
            $table->timestamps();

            $table->index('sentdate');
            $table->index('mobile');
            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_sms');
    }
};
