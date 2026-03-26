<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sv_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vin', 50)->unique()->comment('Vehicle Identification / Frame Number');
            $table->string('model', 100);
            $table->string('variant', 100)->nullable();
            $table->string('color', 60)->nullable();
            $table->year('model_year')->nullable();
            $table->string('engine_no', 60)->nullable();
            $table->string('transmission', 30)->default('Manual')->comment('Manual / Automatic');
            $table->decimal('list_price', 12, 2)->default(0);
            $table->enum('status', ['In Stock', 'Reserved', 'Sold', 'In Transit'])->default('In Stock');
            $table->date('arrival_date')->nullable();
            $table->string('location', 100)->nullable()->comment('Showroom / Yard / Godown');
            $table->text('remarks')->nullable();
            $table->string('added_by', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sv_vehicles');
    }
};
