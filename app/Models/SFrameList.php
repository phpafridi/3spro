<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SFrameList extends Model
{
    use HasFactory;

    protected $table = 's_frame_list';
    protected $primaryKey = 'f_id';
    public $timestamps = true;

    protected $fillable = [
        'uploaded_date',
        'full_frame',
        'variant',
        'fram_exactracted',
        'f_veh_id',
        'f_cust_id',
        'last_visit',
        'Assignto',
        'Assigntime',
        'Actiontaken',
        'status'
    ];

    protected $casts = [
        'uploaded_date' => 'date',
        'last_visit' => 'date',
        'Assigntime' => 'datetime',
        'Actiontaken' => 'datetime'
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(VehiclesData::class, 'f_veh_id', 'Vehicle_id');
    }

    public function customer()
    {
        return $this->belongsTo(CustomerData::class, 'f_cust_id', 'Customer_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'Assignto', 'login_id');
    }
}
