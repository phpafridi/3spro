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
        Schema::create('p_jobber', function (Blueprint $table) {
            $table->integer('jobber_id', true);
            $table->string('jbr_name', 55)->unique();
            $table->string('Job_cust', 18);
            $table->string('person', 35);
            $table->integer('Balance_status');
            $table->text('latest_update');
            $table->dateTime('last_update');
            $table->string('contact', 35);
            $table->string('address', 255);
            $table->string('email', 35);
            $table->string('cnic', 43);
            $table->string('user', 15);
            $table->dateTime('datetime');
            $table->timestamps();

            $table->index('jbr_name');
            $table->index('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_jobber');
    }
};
