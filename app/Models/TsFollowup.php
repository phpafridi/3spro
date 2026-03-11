<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsFollowup extends Model
{
    use HasFactory;

    protected $table = 'ts_followups';
    protected $primaryKey = 'followup_id';
    public $timestamps = true;

    protected $fillable = [
        'query_id',
        'followup',
        'datetime',
        'user'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function tsureService()
    {
        return $this->belongsTo(TsureService::class, 'query_id', 'tsure_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }
}
