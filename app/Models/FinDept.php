<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinDept extends Model
{
    use HasFactory;

    protected $table = 'fin_dept';
    protected $primaryKey = 'dep_auto';
    public $timestamps = true;

    protected $fillable = [
        'Code',
        'Department'
    ];
}
