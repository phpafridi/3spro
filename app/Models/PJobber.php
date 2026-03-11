<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PJobber extends Model
{
    use HasFactory;

    protected $table = 'p_jobber';
    protected $primaryKey = 'jobber_id';
    public $timestamps = true;

    protected $fillable = [
        'jbr_name',
        'Job_cust',
        'person',
        'Balance_status',
        'latest_update',
        'last_update',
        'contact',
        'address',
        'email',
        'cnic',
        'user',
        'datetime'
    ];

    protected $casts = [
        'last_update' => 'datetime',
        'datetime' => 'datetime'
    ];

    // Relationships
    public function purchaseInvoices()
    {
        return $this->hasMany(PPurchInv::class, 'jobber', 'jbr_name');
    }

    public function saleInvoices()
    {
        return $this->hasMany(PSaleInv::class, 'Jobber', 'jbr_name');
    }

    public function payments()
    {
        return $this->hasMany(PJobberPayment::class, 'jobber', 'jbr_name');
    }
}
