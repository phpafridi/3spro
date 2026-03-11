<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecovAccount extends Model
{
    use HasFactory;

    protected $table = 'recov_accounts';
    protected $primaryKey = 'account_id';
    public $timestamps = true;

    protected $fillable = [
        'Name',
        'Occopation',
        'Primary_contact',
        'Sec_contact',
        'email',
        'amount_limit',
        'r_officer',
        'datetime',
        'status'
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'Primary_contact' => 'integer',
        'Sec_contact' => 'integer'
    ];

    // Relationships
    public function recoveryOfficer()
    {
        return $this->belongsTo(User::class, 'r_officer', 'login_id');
    }

    public function debts()
    {
        return $this->hasMany(RecovDebt::class, 'cust_name', 'Name');
    }

    public function credits()
    {
        return $this->hasMany(RecovCred::class, 'cust_name', 'Name');
    }

    public function followups()
    {
        return $this->hasMany(RecovFallowon::class, 'cust_name', 'Name');
    }
}
