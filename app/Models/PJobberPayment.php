<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PJobberPayment extends Model
{
    use HasFactory;

    protected $table = 'p_jobber_payments';
    protected $primaryKey = 'payment_id';
    public $timestamps = true;

    protected $fillable = [
        'jobber',
        'trans_type',
        'amount',
        'payment_method',
        'rec_paid_by',
        'remarks',
        'user',
        'datetime'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function jobber()
    {
        return $this->belongsTo(PJobber::class, 'jobber', 'jbr_name');
    }
}
