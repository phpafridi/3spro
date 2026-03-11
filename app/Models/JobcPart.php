<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcPart extends Model
{
    use HasFactory;

    protected $table = 'jobc_parts';
    protected $primaryKey = 'parts_sale_id';
    public $timestamps = true;

    protected $fillable = [
        'part_invoice_no',
        'RO_no',
        'part_number',
        'part_description',
        'entry_datetime',
        'qty',
        'req_qty',
        'issued_qty',
        'unitprice',
        'total',
        'issue_to',
        'issue_time',
        'Additional',
        'Stock_id',
        'status',
        'issue_by',
        'p_return',
        'incentive_status'
    ];

    protected $casts = [
        'entry_datetime' => 'datetime',
        'issue_time' => 'datetime',
        'Additional' => 'integer'
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
        return $this->hasMany(JobcPartsReturn::class, 'sale_id', 'parts_sale_id');
    }

    public function part()
    {
        return $this->belongsTo(PPart::class, 'part_number', 'Part_no');
    }
}
