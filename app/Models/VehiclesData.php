<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclesData extends Model
{
    use HasFactory;

    protected $table = 'vehicles_data';
    protected $primaryKey = 'Vehicle_id';
    public $timestamps = true;

    protected $fillable = [
        'cust_id',
        'Customer_id',
        'Registration',
        'Frame_no',
        'Model',
        'Variant',
        'Colour',
        'Make',
        'Engine_Code',
        'Engine_number',
        'Wrnty_book_no',
        'Insurance',
        'Update_date',
        'into_sell',
        'model_year',
        'demand_price',
        'user',
        'own_vehicle',
        'updated_by',
        'v_status'
    ];

    protected $casts = [
        'Update_date' => 'datetime'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(CustomerData::class, 'cust_id', 'Customer_id');
    }

    public function jobCards()
    {
        return $this->hasMany(Jobcard::class, 'Vehicle_id', 'Vehicle_id');
    }

    public function appointments()
    {
        return $this->hasMany(CrAppointment::class, 'veh_id', 'Vehicle_id');
    }

    public function pmCalls()
    {
        return $this->hasMany(CrPmcall1::class, 'veh_id', 'Vehicle_id');
    }

    public function dormantFollowups()
    {
        return $this->hasMany(CrDormantfollowup::class, 'veh_id', 'Vehicle_id');
    }
}
