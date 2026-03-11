<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDataDel extends Model
{
    use HasFactory;

    protected $table = 'customer_data_del';
    protected $primaryKey = 'del_cust_id';
    public $timestamps = true;

    protected $fillable = [
        'Customer_id',
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
        'user'
    ];

    protected $casts = [
        'DOB' => 'date',
        'Update_date' => 'datetime'
    ];
}
