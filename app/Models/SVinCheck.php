<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SVinCheck extends Model
{
    use HasFactory;

    protected $table = 's_vin_check';
    protected $primaryKey = 'vin_id';
    public $timestamps = true;

    protected $fillable = [
        'jobcard',
        'frameno',
        'listid',
        'full_vin',
        'ActionTaken',
        'inserteddate',
        'updatedate',
        'doneondate',
        'veh_id',
        'cust_name',
        'cust_id',
        'veh_reg'
    ];

    protected $casts = [
        'inserteddate' => 'datetime',
        'updatedate' => 'datetime'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'jobcard', 'Jobc_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(VehiclesData::class, 'veh_id', 'Vehicle_id');
    }

    public function customer()
    {
        return $this->belongsTo(CustomerData::class, 'cust_id', 'Customer_id');
    }

    public function uploadList()
    {
        return $this->belongsTo(SUploadListname::class, 'listid', 'list_id');
    }

    public function frame()
    {
        return $this->belongsTo(SFrameList::class, 'frameno', 'f_id');
    }
}
