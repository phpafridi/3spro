<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPurchReturn extends Model
{
    use HasFactory;

    protected $table = 'p_purch_return';
    protected $primaryKey = 'p_return_id';
    public $timestamps = true;

    protected $fillable = [
        'PRJV',
        'invoice_no',
        'stock_id',
        'unit_price',
        'return_qty',
        'return_by',
        'reason',
        'user',
        'datetime'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(PPurchInv::class, 'invoice_no', 'Invoice_no');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }
}
