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
        Schema::create('cr_nvd', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('PBO');
            $table->string('status', 35);
            $table->integer('Statisfy');
            $table->integer('visit');
            $table->integer('ffs_info');
            $table->integer('sold');
            $table->string('new_purchaser', 45);
            $table->string('contact', 45);
            $table->string('remarks', 45);
            $table->dateTime('datetime');
            $table->string('cro', 34);
            $table->timestamps();

            $table->index('PBO');
            $table->index('cro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_nvd');
    }
};
