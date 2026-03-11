<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PStockBkup extends Model
{
    use HasFactory;

    protected $table = 'p_stock_bkup';
    public $timestamps = true;

    protected $fillable = [
        'stock_id',
        'part_no',
        'bkup_date',
        'remainging_stock'
    ];

    protected $casts = [
        'bkup_date' => 'date'
    ];

    // Relationships
    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }

    public function part()
    {
        return $this->belongsTo(PPart::class, 'part_no', 'Part_no');
    }
}
