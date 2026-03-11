<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrFfscall2 extends Model
{
    use HasFactory;

    protected $table = 'cr_ffscall2';
    protected $primaryKey = 'ffscall1_id';
    public $timestamps = true;

    protected $fillable = [
        'delv_id',
        'call_status',
        'veh_sold',
        'current_mileage',
        'ffs_done',
        'appointment',
        'reason',
        'username',
        'calldatentime',
        'new_purchaseer',
        'new_contact'
    ];

    protected $casts = [
        'calldatentime' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'login_id');
    }
}
