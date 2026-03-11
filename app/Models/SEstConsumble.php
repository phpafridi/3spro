<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEstConsumble extends Model
{
    use HasFactory;

    protected $table = 's_est_consumble';
    protected $primaryKey = 'estm_part_id';
    public $timestamps = true;

    protected $fillable = [
        'estm_id',
        'part_number',
        'part_description',
        'entry_datetime',
        'qty',
        'req_qty',
        'unitprice',
        'total',
        'issue_to',
        'issue_time',
        'Stock_id',
        'status',
        'issue_by',
        'type'
    ];

    protected $casts = [
        'entry_datetime' => 'datetime',
        'issue_time' => 'datetime'
    ];

    // Relationships
    public function estimate()
    {
        return $this->belongsTo(SEstimate::class, 'estm_id', 'est_id');
    }

    public function stock()
    {
        return $this->belongsTo(PPurchStock::class, 'Stock_id', 'stock_id');
    }

    public function part()
    {
        return $this->belongsTo(PPart::class, 'part_number', 'Part_no');
    }
}
