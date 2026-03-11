<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrMrsStatus extends Model
{
    use HasFactory;

    protected $table = 'cr_mrs_status';
    protected $primaryKey = 'script_id';
    public $timestamps = true;

    protected $fillable = [
        'script_datetime',
        'fordate',
        'NVD',
        'FFS_sms',
        'FFS_sms_s',
        'FFS_call1',
        'FFS_call1_s',
        'FFS_call2',
        'FFS_call2_s',
        'PM_sms',
        'PM_sms_s',
        'PM_call1',
        'PM_call1_s',
        'PM_call2',
        'PM_call2_s',
        'Appointments',
        'Appointments_s'
    ];

    protected $casts = [
        'script_datetime' => 'datetime',
        'fordate' => 'date'
    ];
}
