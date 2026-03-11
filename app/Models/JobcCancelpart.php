<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcCancelpart extends Model
{
    use HasFactory;

    protected $table = 'jobc_cancelparts';
    protected $primaryKey = 'cancel_id';
    public $timestamps = true;

    protected $fillable = [
        'sa_shey',
        'part_no',
        'stock_id',
        'qty',
        'amount',
        'issue_by',
        'cancel_by',
        'issue_time',
        'cancel_time',
        'RO'
    ];

    protected $casts = [
        'issue_time' => 'datetime',
        'cancel_time' => 'datetime'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'RO', 'RO_no');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }
}
