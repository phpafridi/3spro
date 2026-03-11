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
        Schema::create('s_insurance_companies', function (Blueprint $table) {
            $table->integer('c_id', true);
            $table->string('company_name', 40)->unique();
            $table->string('contact', 30);
            $table->string('email', 30);
            $table->string('contact_person', 30);
            $table->string('Surveyors_names', 30);
            $table->string('Location', 45);
            $table->string('addedby', 30);
            $table->date('when');
            $table->string('status', 14);
            $table->integer('ntn');
            $table->timestamps();

            $table->index('company_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_insurance_companies');
    }
};
