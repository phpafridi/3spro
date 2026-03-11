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
        Schema::create('s_msi_categories', function (Blueprint $table) {
            $table->integer('msi_id', true);
            $table->string('MSI', 15);
            $table->string('ro_type', 15);
            $table->string('service_nature', 25);
            $table->string('Description', 40);
            $table->string('CPUS_Warranty', 15);
            $table->timestamps();

            $table->index('MSI');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_msi_categories');
    }
};
