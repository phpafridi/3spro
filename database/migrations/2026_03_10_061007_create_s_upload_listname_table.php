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
        Schema::create('s_upload_listname', function (Blueprint $table) {
            $table->integer('list_id', true);
            $table->string('list_name', 38);
            $table->date('upload_date');
            $table->string('user', 25);
            $table->integer('status');
            $table->timestamps();

            $table->index('list_name');
            $table->index('upload_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_upload_listname');
    }
};
