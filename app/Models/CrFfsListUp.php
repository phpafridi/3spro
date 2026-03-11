<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrFfsListUp extends Model
{
    use HasFactory;

    protected $table = 'cr_ffs_list_up';
    protected $primaryKey = 'script_id';
    public $timestamps = true;

    protected $fillable = [
        'datey',
        'FFS'
    ];

    protected $casts = [
        'datey' => 'date'
    ];
}
