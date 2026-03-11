<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPurchStock extends Model
{
    use HasFactory;

    protected $table = 'p_purch_stock';
    protected $primaryKey = 'stock_id';
    public $timestamps = true;

    protected $fillable = [
        'date',
        'Invoice_no',
        'part_no',
        'Description',
        'unit',
        'quantity',
        'remain_qty',
        'Price',
        'discount',
        'tax',
        'Netamount',
        'cate_type',
        'location',
        'purch_return',
        'Model'
    ];

    protected $casts = [
        'date' => 'date',
        'Price' => 'decimal:3',
        'Netamount' => 'decimal:3'
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(PPurchInv::class, 'Invoice_no', 'Invoice_no');
    }

    public function part()
    {
        return $this->belongsTo(PPart::class, 'part_no', 'Part_no');
    }

    public function saleParts()
    {
        return $this->hasMany(PSalePart::class, 'stock_id', 'stock_id');
    }

    public function jobParts()
    {
        return $this->hasMany(JobcPart::class, 'Stock_id', 'stock_id');
    }

    public function jobConsumables()
    {
        return $this->hasMany(JobcConsumble::class, 'Stock_id', 'stock_id');
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PPurchReturn::class, 'stock_id', 'stock_id');
    }

    public function saleReturns()
    {
        return $this->hasMany(PSaleReturn::class, 'stock_id', 'stock_id');
    }

    public function backups()
    {
        return $this->hasMany(PStockBkup::class, 'stock_id', 'stock_id');
    }
}
