<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinVchMasEdit extends Model
{
    use HasFactory;

    protected $table = 'fin_vch_mas_edit';
    protected $primaryKey = 'request_id';
    public $timestamps = true;

    protected $fillable = [
        'request_reason',
        'who_requested',
        'request_DD',
        'mas_vch_id',
        'VoucherNo',
        'vchr_type',
        'RefNo',
        'VoucherDate',
        'Payee',
        'BookNo',
        'UserName',
        'A_T',
        'Authenticate',
        'Cancel',
        'submiton',
        'complete_submition'
    ];

    protected $casts = [
        'request_DD' => 'datetime',
        'VoucherDate' => 'datetime',
        'submiton' => 'datetime',
        'complete_submition' => 'datetime',
        'Cancel' => 'boolean'
    ];

    // Relationships
    public function master()
    {
        return $this->belongsTo(FinVchMas::class, 'mas_vch_id', 'mas_vch_id');
    }

    public function childEdits()
    {
        return $this->hasMany(FinVchChldEdit::class, 'request_id', 'request_id');
    }
}
