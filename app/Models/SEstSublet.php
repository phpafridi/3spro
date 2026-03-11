<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEstSublet extends Model
{
    use HasFactory;

    protected $table = 's_est_sublet';
    protected $primaryKey = 'est_sub_id';
    public $timestamps = true;

    protected $fillable = [
        'estm_id',
        'Sublet',
        'unitprice',
        'qty',
        'total'
    ];

    // Relationships
    public function estimate()
    {
        return $this->belongsTo(SEstimate::class, 'estm_id', 'est_id');
    }

    public function vendor()
    {
        return $this->belongsTo(SVendorList::class, 'Sublet', 'vendor_name');
    }
}
