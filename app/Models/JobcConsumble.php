<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcConsumble extends Model
{
    use HasFactory;

    protected $table = 'jobc_consumble';
    protected $primaryKey = 'cons_sale_id';
    public $timestamps = true;

    protected $fillable = [
        'cons_req_no',
        'RO_no',
        'cons_number',
        'cons_description',
        'entry_datetime',
        'qty',
        'req_qty',
        'issued_qty',
        'Stock_id',
        'unitprice',
        'total',
        'issue_to',
        'issue_time',
        'Additional',
        'status',
        'issue_by',
        'p_return',
        'incentive_status'
    ];

    protected $casts = [
        'entry_datetime' => 'datetime',
        'issue_time' => 'datetime'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'RO_no', 'RO_no');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'Stock_id', 'stock_id');
    }

    public function returns()
    {
        return $this->hasMany(JobcConsumbleReturn::class, 'sale_id', 'cons_sale_id');
    }
}
