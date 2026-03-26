<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SvDeliveryOrder extends Model
{
    protected $table = 'sv_delivery_orders';

    protected $fillable = [
        'do_no', 'vehicle_id',
        'customer_name', 'customer_cnic', 'customer_phone', 'customer_address',
        'payment_type',
        'onroad_price', 'discount', 'customer_paid_amount',
        'cash_received',
        'bank_name', 'finance_scheme', 'down_payment', 'loan_amount',
        'tenure_months', 'monthly_installment',
        'do_date', 'delivery_date', 'status', 'remarks', 'created_by',
    ];

    protected $casts = [
        'do_date'              => 'date',
        'delivery_date'        => 'date',
        'onroad_price'         => 'decimal:2',
        'discount'             => 'decimal:2',
        'customer_paid_amount' => 'decimal:2',
        'cash_received'        => 'decimal:2',
        'down_payment'         => 'decimal:2',
        'loan_amount'          => 'decimal:2',
        'monthly_installment'  => 'decimal:2',
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
