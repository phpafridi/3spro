<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclesDataDel extends Model
{
    use HasFactory;

    protected $table = 'vehicles_data_del';
    protected $primaryKey = 'del_veh_id';
    public $timestamps = true;

    protected $fillable = [
        'Vehicle_id',
        'cust_id',
        'Customer_id',
        'Registration',
        'Frame_no',
        'Model',
        'Variant',
        'Colour',
        'Make',
        'Engine_Code',
        'Engine_number',
        'Wrnty_book_no',
        'Insurance',
        'Update_date',
        'into_sell',
        'model_year',
        'demand_price',
        'user',
        'own_vehicle'
    ];

    protected $casts = [
        'Update_date' => 'datetime'
    ];
}
