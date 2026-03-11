<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrPsfu extends Model
{
    use HasFactory;

    protected $table = 'cr_psfu';
    protected $primaryKey = 'psfu_id';
    public $timestamps = true;

    protected $fillable = [
        'RO',
        'call_status',
        'q1',
        'q11',
        'q12',
        'q13',
        'q2',
        'q21',
        'q3',
        'q31',
        'q4',
        'q41',
        'Remarks',
        'Datetime',
        'CRO'
    ];

    protected $casts = [
        'q13' => 'date',
        'Datetime' => 'datetime'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'RO', 'RO_no');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'CRO', 'login_id');
    }
}
