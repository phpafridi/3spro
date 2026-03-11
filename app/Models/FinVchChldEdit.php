<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinVchChldEdit extends Model
{
    use HasFactory;

    protected $table = 'fin_vch_chld_edit';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'request_id',
        'chld_vch_id',
        'mas_vch_id',
        'GSL_code',
        'SNO',
        'VoucherNo',
        'RefNo',
        'Department',
        'GSL',
        'Description',
        'Credit',
        'Debit',
        'DM_TradeIn',
        'DM_No',
        'TradeIN_info',
        'PBO_No',
        'Investor',
        'Variant',
        'ModeOfPayment',
        'Activity',
        'mType',
        'Unit',
        'Region',
        'vchr_type',
        'user',
        'submittime'
    ];

    protected $casts = [
        'submittime' => 'datetime',
        'Credit' => 'float',
        'Debit' => 'float',
        'TradeIN_info' => 'integer',
        'mType' => 'integer',
        'Region' => 'integer'
    ];

    // Relationships
    public function masterEdit()
    {
        return $this->belongsTo(FinVchMasEdit::class, 'request_id', 'request_id');
    }

    public function gslAccount()
    {
        return $this->belongsTo(FinGsl::class, 'GSL_code', 'GSL_code');
    }
}
