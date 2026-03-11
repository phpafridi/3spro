<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcSublet extends Model
{
    use HasFactory;

    protected $table = 'jobc_sublet';
    protected $primaryKey = 'sublet_id';
    public $timestamps = true;

    protected $fillable = [
        'RO_no',
        'Sublet',
        'type',
        'qty',
        'unitprice',
        'total',
        'entry_datetime',
        'additional',
        'status',
        'jc',
        'end_time',
        'Asign_time',
        'parts_details',
        'Vendor',
        'who_taking',
        'Vendor_price',
        'logistics'
    ];

    protected $casts = [
        'entry_datetime' => 'datetime',
        'end_time' => 'datetime',
        'Asign_time' => 'datetime'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'RO_no', 'RO_no');
    }

    public function vendor()
    {
        return $this->belongsTo(SVendorList::class, 'Vendor', 'vendor_name');
    }
}
