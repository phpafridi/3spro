<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcLabor extends Model
{
    use HasFactory;

    protected $table = 'jobc_labor';
    protected $primaryKey = 'Labor_id';
    public $timestamps = true;

    protected $fillable = [
        'RO_no',
        'Labor',
        'type',
        'cost',
        'Additional',
        'reason',
        'estimated_time',
        'status',
        'entry_time',
        'Assign_time',
        'end_time',
        'team',
        'bay',
        'remarks',
        'resumetime',
        'jc'
    ];

    protected $casts = [
        'estimated_time' => 'datetime',
        'entry_time' => 'datetime',
        'Assign_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'RO_no', 'RO_no');
    }

    public function team()
    {
        return $this->belongsTo(STechteam::class, 'team', 'team_name');
    }

    public function bay()
    {
        return $this->belongsTo(SBay::class, 'bay', 'bay_name');
    }
}
