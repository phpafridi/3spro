<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SvDeliveryOrder extends Model
{
    protected $table = 'sv_delivery_orders';

    protected $fillable = [
        'do_no', 'pbo_no', 'customer_type', 'sale_price',
        'vehicle_id',
        'customer_name', 'customer_cnic', 'customer_phone', 'customer_address',
        'customer_son_wife_of',
        'payment_type',
        'onroad_price', 'discount', 'customer_paid_amount',
        'cash_received',
        'bank_name', 'finance_scheme', 'down_payment', 'loan_amount',
        'tenure_months', 'monthly_installment',
        'do_date', 'delivery_date', 'status', 'remarks', 'created_by',
        // Receiver
        'receiver_name', 'receiver_father_name', 'receiver_cnic',
        'receiver_phone', 'receiver_address',
        // Accessories
        'acc_keys_qty', 'acc_remote_control', 'acc_toolkit_jack',
        'acc_spare_wheel', 'acc_battery_warranty', 'acc_service_warranty',
        // Documents
        'doc_sales_invoice', 'doc_sales_certificate', 'doc_sales_cert_verification',
        // NVD Checklist
        'nvd_warranty_terms', 'nvd_owners_manual', 'nvd_ffs_pm_schedule',
        'nvd_3s_visit', 'nvd_ew_ppm', 'nvd_safety_features', 'nvd_demonstrated_ops',
    ];

    protected $casts = [
        'do_date'              => 'date',
        'delivery_date'        => 'date',
        'onroad_price'         => 'decimal:2',
        'discount'             => 'decimal:2',
        'customer_paid_amount' => 'decimal:2',
        'sale_price'           => 'decimal:2',
        'cash_received'        => 'decimal:2',
        'down_payment'         => 'decimal:2',
        'loan_amount'          => 'decimal:2',
        'monthly_installment'  => 'decimal:2',
        // boolean accessories
        'acc_remote_control'   => 'boolean',
        'acc_toolkit_jack'     => 'boolean',
        'acc_spare_wheel'      => 'boolean',
        'acc_battery_warranty' => 'boolean',
        'acc_service_warranty' => 'boolean',
        // boolean documents
        'doc_sales_invoice'          => 'boolean',
        'doc_sales_certificate'      => 'boolean',
        'doc_sales_cert_verification'=> 'boolean',
        // boolean NVD checklist
        'nvd_warranty_terms'   => 'boolean',
        'nvd_owners_manual'    => 'boolean',
        'nvd_ffs_pm_schedule'  => 'boolean',
        'nvd_3s_visit'         => 'boolean',
        'nvd_ew_ppm'           => 'boolean',
        'nvd_safety_features'  => 'boolean',
        'nvd_demonstrated_ops' => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function vehicle()
    {
        return $this->belongsTo(SvVehicle::class, 'vehicle_id');
    }

    // ── Helper ───────────────────────────────────────────────────
    public static function generateDoNo(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->lockForUpdate()->count();
        return 'DO-' . $year . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
