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
        Schema::create('msi_category', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('MSI_CAT', 10);
            $table->text('Description');
            $table->string('CPUS_Warranty', 48);
            $table->string('PM_GM', 48);
            $table->text('Labor');
            $table->timestamps();

            $table->index('MSI_CAT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msi_category');
    }
};
