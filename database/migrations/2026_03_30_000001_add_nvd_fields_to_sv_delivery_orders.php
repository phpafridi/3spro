<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the NVD (New Vehicle Delivery) fields that appear on the
 * Vehicle Delivery & Acceptance Note print form.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sv_delivery_orders', function (Blueprint $table) {

            // ── Header fields ─────────────────────────────────────────────
            $table->string('pbo_no', 50)->nullable()->after('do_no')
                  ->comment('Purchase / Booking Order number  e.g. 001');
            $table->enum('customer_type', ['Investor', 'Corporate', 'Individual'])
                  ->default('Individual')->after('pbo_no');
            $table->decimal('sale_price', 12, 2)->nullable()->after('customer_type')
                  ->comment('Final agreed sale price (may differ from onroad_price)');

            // ── Customer extra ────────────────────────────────────────────
            $table->string('customer_son_wife_of', 150)->nullable()->after('customer_address')
                  ->comment('S/o or W/o field on the NVD form');

            // ── Vehicle Receiver (if different from buyer) ────────────────
            $table->string('receiver_name', 150)->nullable();
            $table->string('receiver_father_name', 150)->nullable();
            $table->string('receiver_cnic', 20)->nullable();
            $table->string('receiver_phone', 20)->nullable();
            $table->string('receiver_address')->nullable();

            // ── Accessories (stored as JSON booleans) ─────────────────────
            $table->unsignedTinyInteger('acc_keys_qty')->default(1)
                  ->comment('Number of keys handed over');
            $table->boolean('acc_remote_control')->default(true);
            $table->boolean('acc_toolkit_jack')->default(true);
            $table->boolean('acc_spare_wheel')->default(false);
            $table->boolean('acc_battery_warranty')->default(false);
            $table->boolean('acc_service_warranty')->default(true);

            // ── Documents ─────────────────────────────────────────────────
            $table->boolean('doc_sales_invoice')->default(false);
            $table->boolean('doc_sales_certificate')->default(false);
            $table->boolean('doc_sales_cert_verification')->default(false);

            // ── NVD Checklist ─────────────────────────────────────────────
            $table->boolean('nvd_warranty_terms')->default(false);
            $table->boolean('nvd_owners_manual')->default(false);
            $table->boolean('nvd_ffs_pm_schedule')->default(false);
            $table->boolean('nvd_3s_visit')->default(false);
            $table->boolean('nvd_ew_ppm')->default(false);
            $table->boolean('nvd_safety_features')->default(false);
            $table->boolean('nvd_demonstrated_ops')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('sv_delivery_orders', function (Blueprint $table) {
            $table->dropColumn([
                'pbo_no', 'customer_type', 'sale_price',
                'customer_son_wife_of',
                'receiver_name', 'receiver_father_name', 'receiver_cnic',
                'receiver_phone', 'receiver_address',
                'acc_keys_qty', 'acc_remote_control', 'acc_toolkit_jack',
                'acc_spare_wheel', 'acc_battery_warranty', 'acc_service_warranty',
                'doc_sales_invoice', 'doc_sales_certificate', 'doc_sales_cert_verification',
                'nvd_warranty_terms', 'nvd_owners_manual', 'nvd_ffs_pm_schedule',
                'nvd_3s_visit', 'nvd_ew_ppm', 'nvd_safety_features', 'nvd_demonstrated_ops',
            ]);
        });
    }
};
