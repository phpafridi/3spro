<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_call_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('jobc_id')->comment('Jobcard / RO number');
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('customer_name', 150)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('registration', 30)->nullable();

            // Call type: FFS / PSFU / ASFU / CSF / CFU
            $table->enum('call_type', ['FFS', 'PSFU', 'ASFU', 'CSF', 'CFU'])->default('FFS');

            // Call outcome
            $table->enum('call_status', ['Contacted', 'Not Reachable', 'Callback Requested', 'Voicemail', 'Wrong Number'])->default('Contacted');

            $table->text('remarks')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->dateTime('called_at');
            $table->string('called_by', 60)->nullable();
            $table->timestamps();

            $table->index('jobc_id');
            $table->index('customer_id');
            $table->index('called_at');
            $table->index('next_followup_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_call_logs');
    }
};
