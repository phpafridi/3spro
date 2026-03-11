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
        Schema::create('ts_followups', function (Blueprint $table) {
            $table->integer('followup_id', true);
            $table->integer('query_id');
            $table->text('followup');
            $table->dateTime('datetime');
            $table->string('user', 30);
            $table->timestamps();

            $table->index('query_id');
            $table->index('datetime');
            $table->index('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ts_followups');
    }
};
