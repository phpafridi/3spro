<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrAppointment extends Model
{
    use HasFactory;

    protected $table = 'cr_appointments';
    protected $primaryKey = 'app_id';
    public $timestamps = true;

    protected $fillable = [
        'CustomerName',
        'Mobile',
        'job_nature',
        'cust_id',
        'veh_id',
        'source',
        'source_id',
        'veh_rec',
        'veh_details',
        'ro_no',
        'VOC',
        'bay',
        'labor',
        'parts',
        'Labor_cost',
        'Parts_cost',
        'parts_status',
        'parts_user',
        'parts_datatime',
        'appt_datetime',
        'Deliverytime',
        'mature',
        'mature_datetime',
        'SA',
        'CRO',
        'appt_entry_datetime',
        'Variant',
        'app_status',
        'remarks',
        'reschedule_by'
    ];

    protected $casts = [
        'parts_datatime' => 'datetime',
        'appt_datetime' => 'datetime',
        'Deliverytime' => 'datetime',
        'mature_datetime' => 'datetime',
        'appt_entry_datetime' => 'datetime'
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

    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'ro_no', 'RO_no');
    }
}
