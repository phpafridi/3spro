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
        Schema::create('fin_gl', function (Blueprint $table) {
            $table->integer('GL_id', true);
            $table->integer('ma_id');
            $table->string('GL_name', 35);
            $table->string('user', 35);
            $table->timestamp('datetime')->useCurrent()->useCurrentOnUpdate();
            $table->string('gl_status', 25);
            $table->integer('rang_start');
            $table->integer('rang_end');
            $table->tinyInteger('LinkMe')->unsigned()->nullable();
            $table->string('Link', 3)->nullable();
            $table->integer('GlCode');
            $table->integer('End')->nullable();
            $table->string('Name', 100)->nullable();
            $table->integer('GGLCodeLink')->nullable();
            $table->timestamps();

            $table->index('ma_id');
            $table->index('GL_id');
            $table->index('GlCode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_gl');
    }
};
