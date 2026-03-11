<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecovFallowon extends Model
{
    use HasFactory;

    protected $table = 'recov_fallowons';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'cust_name',
        'Datetime',
        'Person_contacted',
        'Contact_type',
        'Remarks'
    ];

    protected $casts = [
        'Datetime' => 'date'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(RecovAccount::class, 'cust_name', 'Name');
    }
}
