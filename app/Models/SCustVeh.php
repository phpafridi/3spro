<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SCustVeh extends Model
{
    use HasFactory;

    protected $table = 's_cust_veh';
    protected $primaryKey = 'iddd';
    public $timestamps = true;

    protected $fillable = [
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
