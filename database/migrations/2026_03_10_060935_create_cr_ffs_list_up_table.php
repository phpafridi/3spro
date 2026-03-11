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
        Schema::create('cr_ffs_list_up', function (Blueprint $table) {
            $table->integer('script_id', true);
            $table->date('datey');
            $table->integer('FFS');
            $table->timestamps();

            $table->index('datey');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_ffs_list_up');
    }
};
