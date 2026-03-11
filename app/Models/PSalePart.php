<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSalePart extends Model
{
    use HasFactory;

    protected $table = 'p_sale_part';
    protected $primaryKey = 'sell_id';
    public $timestamps = true;

    protected $fillable = [
        'sale_inv',
        'stock_id',
        'part_no',
        'Description',
        'quantity',
        'sale_price',
        'discount',
        'tax',
        'netamount',
        'SRJV_return',
        'remain_qty'
    ];

    // Relationships
    public function saleInvoice()
    {
        return $this->belongsTo(PSaleInv::class, 'sale_inv', 'sale_inv');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }

    public function part()
    {
        return $this->belongsTo(PPart::class, 'part_no', 'Part_no');
    }

    public function returns()
    {
        return $this->hasMany(PSaleReturn::class, 'sell_id', 'sell_id');
    }
}
