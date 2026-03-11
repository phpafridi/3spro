<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPboVc extends Model
{
    use HasFactory;

    protected $table = 'sales_pbo_vc';
    protected $primaryKey = 'ids_id';
    public $timestamps = true;

    protected $fillable = [
        'pbo',
        'cust_id',
        'veh_id'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(CustomerData::class, 'cust_id', 'Customer_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(VehiclesData::class, 'veh_id', 'Vehicle_id');
    }
}
