<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrProblemTray extends Model
{
    use HasFactory;

    protected $table = 'cr_problem_tray';
    protected $primaryKey = 'p_id';
    public $timestamps = true;

    protected $fillable = [
        'source_by',
        'source_id',
        'cust_id',
        'veh_id',
        'problem',
        'customer_name',
        'Contact',
        'remarks',
        'cro',
        'fdatetime',
        'status',
        'prev_data',
        'updated_data',
        'who_did',
        'when_did',
        'ActionTaken',
        'ActionCompleted',
        'Completed',
        'messageforsa'
    ];

    protected $casts = [
        'fdatetime' => 'datetime',
        'when_did' => 'datetime'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'cro', 'login_id');
    }
}
