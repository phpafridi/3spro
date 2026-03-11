<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecovCred extends Model
{
    use HasFactory;

    protected $table = 'recov_cred';
    protected $primaryKey = 'cred_id';
    public $timestamps = true;

    protected $fillable = [
        'cust_name',
        'dm_invoice',
        'Payment_method',
        'RT_no',
        'cr_date',
        'cr_amount',
        'remarks',
        'user',
        'entytime'
    ];

    protected $casts = [
        'cr_date' => 'date',
        'entytime' => 'datetime'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(RecovAccount::class, 'cust_name', 'Name');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }
}
