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
        Schema::create('s_campaigns', function (Blueprint $table) {
            $table->integer('campaign_id', true);
            $table->string('campaign_name', 255);
            $table->string('nature', 255);
            $table->date('c_from');
            $table->date('c_to');
            $table->string('status', 20);
            $table->string('user', 35);
            $table->dateTime('datetime');
            $table->integer('LC');
            $table->timestamps();

            $table->index('campaign_name');
            $table->index('c_from');
            $table->index('c_to');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_campaigns');
    }
};
