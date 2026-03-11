<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsureService extends Model
{
    use HasFactory;

    protected $table = 'tsure_service';
    protected $primaryKey = 'tsure_id';
    public $timestamps = true;

    protected $fillable = [
        'veh_id',
        'cust_id',
        'demand_price',
        'Next_followup',
        'RNP',
        'q_status',
        'user',
        'datetime',
        'SA'
    ];

    protected $casts = [
        'Next_followup' => 'date',
        'datetime' => 'datetime'
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

    public function serviceAdvisor()
    {
        return $this->belongsTo(User::class, 'SA', 'login_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }

    public function followups()
    {
        return $this->hasMany(TsFollowup::class, 'query_id', 'tsure_id');
    }
}
