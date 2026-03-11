<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrNvd extends Model
{
    use HasFactory;

    protected $table = 'cr_nvd';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'PBO',
        'status',
        'Statisfy',
        'visit',
        'ffs_info',
        'sold',
        'new_purchaser',
        'contact',
        'remarks',
        'datetime',
        'cro'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'cro', 'login_id');
    }
}
