<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinVchChld extends Model
{
    use HasFactory;

    protected $table = 'fin_vch_chld';
    protected $primaryKey = 'chld_vch_id';
    public $timestamps = true;

    protected $fillable = [
        'mas_vch_id',
        'GSL_code',
        'SNO',
        'VoucherNo',
        'RefNo',
        'Department',
        'GSL',
        'Description',
        'Debit',
        'Credit',
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
        'submittime',
        'payee'
    ];

    protected $casts = [
        'submittime' => 'datetime',
        'Debit' => 'float',
        'Credit' => 'float',
        'TradeIN_info' => 'integer',
        'mType' => 'integer',
        'Activity' => 'integer'
    ];

    // Relationships
    public function master()
    {
        return $this->belongsTo(FinVchMas::class, 'mas_vch_id', 'mas_vch_id');
    }

    public function gslAccount()
    {
        return $this->belongsTo(FinGsl::class, 'GSL_code', 'GSL_code');
    }

    public function department()
    {
        return $this->belongsTo(FinDept::class, 'Department', 'dep_auto');
    }
}
