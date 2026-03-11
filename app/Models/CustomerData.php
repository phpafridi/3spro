<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerData extends Model
{
    use HasFactory;

    protected $table = 'customer_data';
    protected $primaryKey = 'Customer_id';
    public $timestamps = true;

    protected $fillable = [
        'old_id',
        'Vehicle_id',
        'cust_type',
        'Customer_name',
        'off_phone',
        'mobile',
        'Address',
        'DOB',
        'City',
        'Region',
        'email',
        'Update_date',
        'CNIC',
        'contact_type',
        'NTN',
        'STRN',
        'Supplier',
        'user',
        'updated_by',
        'c_status'
    ];

    protected $casts = [
        'DOB' => 'date',
        'Update_date' => 'datetime'
    ];

    // Relationships
    public function vehicles()
    {
        return $this->hasMany(VehiclesData::class, 'cust_id', 'Customer_id');
    }

    public function appointments()
    {
        return $this->hasMany(CrAppointment::class, 'cust_id', 'Customer_id');
    }

    public function jobCards()
    {
        return $this->hasMany(Jobcard::class, 'Customer_id', 'Customer_id');
    }
}
