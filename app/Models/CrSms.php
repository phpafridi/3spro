<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrSms extends Model
{
    use HasFactory;

    protected $table = 'cr_sms';
    protected $primaryKey = 'sms_id';
    public $timestamps = true;

    protected $fillable = [
        'sentdate',
        'source',
        'source_id',
        'mobile',
        'status',
        'cro'
    ];

    protected $casts = [
        'sentdate' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'cro', 'login_id');
    }
}
