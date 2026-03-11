<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcChecklist extends Model
{
    use HasFactory;

    protected $table = 'jobc_checklist';
    protected $primaryKey = 'RO_id';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'RO_id',
        'usb',
        'cardreader',
        'ashtray',
        'lighter',
        'wiperblader',
        'seatcover',
        'dickymat',
        'sparewheel',
        'jackhandle',
        'tools',
        'perfume',
        'remote',
        'floormate',
        'mirror',
        'cassete',
        'hubcaps',
        'wheelcaps',
        'monogram',
        'extrakeys',
        'anttena',
        'clock',
        'Navigation'
    ];

    protected $casts = [
        'usb' => 'integer',
        'cardreader' => 'integer',
        'ashtray' => 'integer',
        'lighter' => 'integer',
        'wiperblader' => 'integer',
        'seatcover' => 'integer',
        'dickymat' => 'integer',
        'sparewheel' => 'integer',
        'jackhandle' => 'integer',
        'tools' => 'integer',
        'perfume' => 'integer',
        'remote' => 'integer',
        'floormate' => 'integer',
        'mirror' => 'integer',
        'cassete' => 'integer',
        'hubcaps' => 'integer',
        'wheelcaps' => 'integer',
        'monogram' => 'integer',
        'extrakeys' => 'integer',
        'anttena' => 'integer',
        'clock' => 'integer',
        'Navigation' => 'integer'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'RO_id', 'RO_no');
    }
}
