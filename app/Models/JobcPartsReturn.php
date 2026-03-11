<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcPartsReturn extends Model
{
    use HasFactory;

    protected $table = 'jobc_parts_return';
    protected $primaryKey = 'return_id';
    public $timestamps = true;

    protected $fillable = [
        'sale_id',
        'stock_id',
        'return_qty',
        'return_amount',
        'datetime',
        'user'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function part()
    {
        return $this->belongsTo(JobcPart::class, 'sale_id', 'parts_sale_id');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }
}
