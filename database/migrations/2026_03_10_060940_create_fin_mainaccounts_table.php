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
        Schema::create('fin_mainaccounts', function (Blueprint $table) {
            $table->integer('ma_id', true);
            $table->string('main_account', 45)->unique();
            $table->string('ma_user', 25);
            $table->dateTime('ma_datetime');
            $table->string('ma_status', 25);
            $table->integer('rang_start');
            $table->integer('rang_end');
            $table->timestamps();

            $table->index('ma_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_mainaccounts');
    }
};
