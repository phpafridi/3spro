<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSaleReturn extends Model
{
    use HasFactory;

    protected $table = 'p_sale_return';
    protected $primaryKey = 's_return_id';
    public $timestamps = true;

    protected $fillable = [
        'SRJV',
        'invoice_no',
        'stock_id',
        'sell_id',
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
    public function saleInvoice()
    {
        return $this->belongsTo(PSaleInv::class, 'invoice_no', 'sale_inv');
    }

    public function salePart()
    {
        return $this->belongsTo(PSalePart::class, 'sell_id', 'sell_id');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }
}
