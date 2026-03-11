<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcConsumbleP extends Model
{
    use HasFactory;

    protected $table = 'jobc_consumble_p';
    protected $primaryKey = 'workshop_id';
    public $timestamps = true;

    protected $fillable = [
        'parts_sale_id',
        'stock_id',
        'part_no',
        'req_qty',
        'qty_issued',
        'user',
        'date_time',
        'issued_qty'
    ];

    protected $casts = [
        'date_time' => 'datetime'
    ];

    // Relationships
    public function consumable()
    {
        return $this->belongsTo(JobcConsumble::class, 'parts_sale_id', 'cons_sale_id');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }
}
