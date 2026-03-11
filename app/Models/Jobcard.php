<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobcard extends Model
{
    use HasFactory;

    protected $table = 'jobcard';
    protected $primaryKey = 'Jobc_id';
    public $timestamps = true;

    protected $fillable = [
        'Customer_name',
        'Veh_reg_no',
        'Vehicle_id',
        'Customer_id',
        'Open_date_time',
        'comp_appointed',
        'cust_source',
        'MSI_cat',
        'serv_nature',
        'RO_type',
        'Fuel',
        'cust_waiting',
        'Mileage',
        'VOC',
        'Estim_time',
        'Estim_cost',
        'SA',
        'Diagnose_by',
        'status',
        'closing_time',
        'rating_done',
        'PSFU',
        'PM_status',
        'RO_no'
    ];

    protected $casts = [
        'Open_date_time' => 'datetime',
        'Estim_time' => 'datetime',
        'closing_time' => 'datetime'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(CustomerData::class, 'Customer_id', 'Customer_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(VehiclesData::class, 'Vehicle_id', 'Vehicle_id');
    }

    public function serviceAdvisor()
    {
        return $this->belongsTo(User::class, 'SA', 'login_id');
    }

    public function appointment()
    {
        return $this->hasOne(CrAppointment::class, 'ro_no', 'RO_no');
    }

    public function laborItems()
    {
        return $this->hasMany(JobcLabor::class, 'RO_no', 'RO_no');
    }

    public function parts()
    {
        return $this->hasMany(JobcPart::class, 'RO_no', 'RO_no');
    }

    public function sublets()
    {
        return $this->hasMany(JobcSublet::class, 'RO_no', 'RO_no');
    }

    public function consumables()
    {
        return $this->hasMany(JobcConsumble::class, 'RO_no', 'RO_no');
    }

    public function invoice()
    {
        return $this->hasOne(JobcInvoice::class, 'Jobc_id', 'Jobc_id');
    }

    public function checklist()
    {
        return $this->hasOne(JobcChecklist::class, 'RO_id', 'RO_no');
    }

    public function rating()
    {
        return $this->hasOne(CustomerRating::class, 'RO', 'RO_no');
    }

    public function psfu()
    {
        return $this->hasOne(CrPsfu::class, 'RO', 'RO_no');
    }

    public function warranty()
    {
        return $this->hasOne(SWarranty::class, 'jobc_id', 'Jobc_id');
    }
}
