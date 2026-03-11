<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrPmListUp extends Model
{
    use HasFactory;

    protected $table = 'cr_pm_list_up';
    protected $primaryKey = 'script_id';
    public $timestamps = true;

    protected $fillable = [
        'datey',
        'PM',
        'PM_OWN'
    ];

    protected $casts = [
        'datey' => 'date'
    ];
}
