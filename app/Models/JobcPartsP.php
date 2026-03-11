<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcPartsP extends Model
{
    use HasFactory;

    protected $table = 'jobc_parts_p';
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
    public function part()
    {
        return $this->belongsTo(JobcPart::class, 'parts_sale_id', 'parts_sale_id');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'stock_id', 'stock_id');
    }
}
