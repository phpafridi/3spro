<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SWarranty extends Model
{
    use HasFactory;

    protected $table = 's_warranty';
    protected $primaryKey = 'w_id';
    public $timestamps = true;

    protected $fillable = [
        'jobc_id',
        'wc_no',
        'status',
        'claim_date',
        'approve_date',
        'user',
        'remarks',
        'delivery_date',
        'FFS'
    ];

    protected $casts = [
        'claim_date' => 'date',
        'approve_date' => 'date',
        'delivery_date' => 'date'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'jobc_id', 'Jobc_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }
}
