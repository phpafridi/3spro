<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrDormantfollowup extends Model
{
    use HasFactory;

    protected $table = 'cr_dormantfollowup';
    protected $primaryKey = 'PMcall1_id';
    public $timestamps = true;

    protected $fillable = [
        'veh_id',
        'call_status',
        'veh_sold',
        'current_mileage',
        'PM_done',
        'appointment',
        'reason',
        'username',
        'calldatentime'
    ];

    protected $casts = [
        'calldatentime' => 'datetime'
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(VehiclesData::class, 'veh_id', 'Vehicle_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'login_id');
    }
}
