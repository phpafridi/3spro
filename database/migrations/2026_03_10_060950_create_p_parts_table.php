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
        Schema::create('p_parts', function (Blueprint $table) {
            $table->integer('part_id', true);
            $table->string('Part_no', 35)->unique();
            $table->text('Description');
            $table->string('Location', 40);
            $table->string('catetype', 15);
            $table->string('part_type', 15);
            $table->string('Model', 35);
            $table->integer('ReOrder');
            $table->string('user', 20);
            $table->dateTime('datetime');
            $table->timestamps();

            $table->index('Part_no');
            $table->index('Model');
            $table->index('catetype');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_parts');
    }
};
