<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SvVehicle extends Model
{
    protected $table = 'sv_vehicles';

    protected $fillable = [
        'vin', 'model', 'variant', 'color', 'model_year',
        'engine_no', 'transmission', 'list_price',
        'status', 'arrival_date', 'location', 'remarks', 'added_by',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'list_price'   => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function deliveryOrders()
    {
        return $this->hasMany(SvDeliveryOrder::class, 'vehicle_id');
    }

    public function latestDO()
    {
        return $this->hasOne(SvDeliveryOrder::class, 'vehicle_id')->latestOfMany();
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeInStock($query)
    {
        return $query->where('status', 'In Stock');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'Sold');
    }
}
