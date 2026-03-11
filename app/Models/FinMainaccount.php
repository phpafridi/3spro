<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinMainaccount extends Model
{
    use HasFactory;

    protected $table = 'fin_mainaccounts';
    protected $primaryKey = 'ma_id';
    public $timestamps = true;

    protected $fillable = [
        'main_account',
        'ma_user',
        'ma_datetime',
        'ma_status',
        'rang_start',
        'rang_end'
    ];

    protected $casts = [
        'ma_datetime' => 'datetime'
    ];

    // Relationships
    public function glAccounts()
    {
        return $this->hasMany(FinGl::class, 'ma_id', 'ma_id');
    }
}
