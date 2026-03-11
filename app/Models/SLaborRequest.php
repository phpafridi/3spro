<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SLaborRequest extends Model
{
    use HasFactory;

    protected $table = 's_labor_request';
    protected $primaryKey = 'req_id';
    public $timestamps = true;

    protected $fillable = [
        'labor',
        'cate1',
        'cate2',
        'cate3',
        'cate4',
        'cate5',
        'remarks',
        'status',
        'who_req',
        'when_req',
        'who_acept',
        'when_acept'
    ];

    protected $casts = [
        'when_req' => 'datetime',
        'when_acept' => 'datetime'
    ];

    // Relationships
    public function requester()
    {
        return $this->belongsTo(User::class, 'who_req', 'login_id');
    }

    public function acceptor()
    {
        return $this->belongsTo(User::class, 'who_acept', 'login_id');
    }
}
