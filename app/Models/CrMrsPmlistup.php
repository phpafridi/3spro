<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrMrsPmlistup extends Model
{
    use HasFactory;

    protected $table = 'cr_mrs_pmlistup';
    protected $primaryKey = 'listup_id';
    public $timestamps = true;

    protected $fillable = [
        'source',
        'listupdate',
        'exp_due_date',
        'veh_id',
        'cust_id',
        'own_veh',
        'assign_to',
        'assign_date',
        'cur_mileage',
        'relistup_status',
        'status',
        'app_status',
        'action_taken',
        'Remarks_RO',
        'formula',
        'runed_on'
    ];

    protected $casts = [
        'listupdate' => 'date',
        'exp_due_date' => 'date',
        'assign_date' => 'date',
        'action_taken' => 'date',
        'runed_on' => 'datetime'
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
        return $this->belongsTo(User::class, 'assign_to', 'login_id');
    }
}
