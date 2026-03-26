<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sv_delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('do_no', 30)->unique()->comment('Delivery Order Number e.g. DO-2026-0001');

            // Vehicle
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('sv_vehicles');

            // Customer info
            $table->string('customer_name', 150);
            $table->string('customer_cnic', 20)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_address')->nullable();

            // Payment — pricing
            $table->enum('payment_type', ['Cash', 'Installment', 'Direct'])->default('Cash');
            $table->decimal('onroad_price', 12, 2)->default(0)->comment('Ex-factory + registration + insurance');
            $table->decimal('discount', 12, 2)->default(0)->comment('Discount given to customer');
            $table->decimal('customer_paid_amount', 12, 2)->default(0)->comment('onroad_price - discount');

            // ── Cash fields ──
            $table->decimal('cash_received', 12, 2)->default(0);

            // ── Installment fields ──
            $table->string('bank_name', 100)->nullable();
            $table->string('finance_scheme', 100)->nullable();
            $table->decimal('down_payment', 12, 2)->default(0);
            $table->decimal('loan_amount', 12, 2)->default(0)->comment('customer_paid_amount - down_payment');
            $table->unsignedSmallInteger('tenure_months')->nullable()->comment('Loan tenure in months');
            $table->decimal('monthly_installment', 12, 2)->default(0);

            // DO Meta
            $table->date('do_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Delivered', 'Cancelled'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sv_delivery_orders');
    }
};
