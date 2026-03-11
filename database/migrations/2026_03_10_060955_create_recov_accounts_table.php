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
        Schema::create('recov_accounts', function (Blueprint $table) {
            $table->integer('account_id', true);
            $table->string('Name', 38);
            $table->string('Occopation', 30);
            $table->integer('Primary_contact', false, true)->length(20);
            $table->integer('Sec_contact', false, true)->length(20);
            $table->string('email', 50);
            $table->integer('amount_limit');
            $table->string('r_officer', 25);
            $table->dateTime('datetime');
            $table->text('status');
            $table->timestamps();

            $table->index('Name');
            $table->index('r_officer');
            $table->index('Primary_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recov_accounts');
    }
};
