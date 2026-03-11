<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcConsumbleReturn extends Model
{
    use HasFactory;

    protected $table = 'jobc_consumble_return';
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
    public function consumable()
    {
        return $this->belongsTo(JobcConsumble::class, 'sale_id', 'cons_sale_id');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }
}
