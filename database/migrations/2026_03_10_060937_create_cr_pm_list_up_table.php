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
        Schema::create('cr_pm_list_up', function (Blueprint $table) {
            $table->integer('script_id', true);
            $table->date('datey');
            $table->integer('PM');
            $table->integer('PM_OWN');
            $table->timestamps();

            $table->index('datey');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cr_pm_list_up');
    }
};
