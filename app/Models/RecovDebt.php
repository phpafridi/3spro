<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecovDebt extends Model
{
    use HasFactory;

    protected $table = 'recov_debt';
    protected $primaryKey = 'cust_id';
    public $timestamps = true;

    protected $fillable = [
        'cust_name',
        'contact',
        'Vehicle_name',
        'Registration',
        'Invoice_no',
        'Db_date',
        'Debt_amount',
        'Remarks',
        'user',
        'entytime'
    ];

    protected $casts = [
        'Db_date' => 'date',
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
