<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SUnclosedJc extends Model
{
    use HasFactory;

    protected $table = 's_unclosed_jc';
    protected $primaryKey = 'unjc_Id';
    public $timestamps = true;

    protected $fillable = [
        'jobc_id',
        'total_invoice',
        'old_inv_datime',
        'SM_reason',
        'SM',
        'sm_datetime',
        'fin_reason',
        'fin_datetime',
        'fin_guy',
        'status'
    ];

    protected $casts = [
        'old_inv_datime' => 'datetime',
        'sm_datetime' => 'datetime',
        'fin_datetime' => 'datetime'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'jobc_id', 'Jobc_id');
    }

    public function serviceManager()
    {
        return $this->belongsTo(User::class, 'SM', 'login_id');
    }

    public function financeGuy()
    {
        return $this->belongsTo(User::class, 'fin_guy', 'login_id');
    }
}
