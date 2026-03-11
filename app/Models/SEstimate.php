<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEstimate extends Model
{
    use HasFactory;

    protected $table = 's_estimates';
    protected $primaryKey = 'est_id';
    public $timestamps = true;

    protected $fillable = [
        'cust_id',
        'veh_id',
        'estimate_type',
        'payment_mode',
        'cust_type',
        'insur_company',
        'surv_name',
        'surv_type',
        'est_delivery',
        'user',
        'entry_datetime',
        'est_status',
        'sur_cont'
    ];

    protected $casts = [
        'est_delivery' => 'datetime',
        'entry_datetime' => 'datetime'
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

    public function insuranceCompany()
    {
        return $this->belongsTo(SInsuranceCompany::class, 'insur_company', 'company_name');
    }

    public function surveyor()
    {
        return $this->belongsTo(SInsuranceCompany::class, 'surv_name', 'Surveyors_names');
    }

    public function parts()
    {
        return $this->hasMany(SEstPart::class, 'estm_id', 'est_id');
    }

    public function labors()
    {
        return $this->hasMany(SEstLabor::class, 'estm_id', 'est_id');
    }

    public function sublets()
    {
        return $this->hasMany(SEstSublet::class, 'estm_id', 'est_id');
    }

    public function consumables()
    {
        return $this->hasMany(SEstConsumble::class, 'estm_id', 'est_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }
}
