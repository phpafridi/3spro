<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinVchMas extends Model
{
    use HasFactory;

    protected $table = 'fin_vch_mas';
    protected $primaryKey = 'mas_vch_id';
    public $timestamps = true;

    protected $fillable = [
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
        'complete_submition',
        'VType'
    ];

    protected $casts = [
        'VoucherDate' => 'date',
        'submiton' => 'datetime',
        'complete_submition' => 'datetime',
        'Cancel' => 'boolean'
    ];

    // Relationships
    public function children()
    {
        return $this->hasMany(FinVchChld::class, 'mas_vch_id', 'mas_vch_id');
    }

    public function editRequests()
    {
        return $this->hasMany(FinVchMasEdit::class, 'mas_vch_id', 'mas_vch_id');
    }
}
