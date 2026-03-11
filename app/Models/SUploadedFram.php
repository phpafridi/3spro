<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SUploadedFram extends Model
{
    use HasFactory;

    protected $table = 's_uploaded_frams';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'uploaded_id',
        'VIN',
        'full_VIN',
        'secondary_info',
        'veh_id',
        'cust_id',
        'uploaded_date',
        'Assign_to',
        'Assign_date',
        'Action_taken',
        'status'
    ];

    protected $casts = [
        'uploaded_date' => 'date',
        'Assign_date' => 'date',
        'Action_taken' => 'date'
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(VehiclesData::class, 'veh_id', 'Vehicle_id');
    }

    public function customer()
    {
        return $this->belongsTo(CustomerData::class, 'cust_id', 'Customer_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'Assign_to', 'login_id');
    }

    public function uploadList()
    {
        return $this->belongsTo(SUploadListname::class, 'uploaded_id', 'list_id');
    }
}
