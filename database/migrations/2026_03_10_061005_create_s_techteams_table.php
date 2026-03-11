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
        Schema::create('s_techteams', function (Blueprint $table) {
            $table->integer('team_id', true);
            $table->string('team_name', 12);
            $table->text('members');
            $table->integer('status');
            $table->string('category', 23);
            $table->timestamps();

            $table->index('team_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_techteams');
    }
};
