<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinGsl extends Model
{
    use HasFactory;

    protected $table = 'fin_gsl';
    protected $primaryKey = 'GSL_ID';
    public $timestamps = true;

    protected $fillable = [
        'GL_id',
        'GSL_code',
        'GSL_name',
        'Description',
        'gsl_user',
        'gsl_datetime',
        'gsl_status',
        'GLCode',
        'GSLCode',
        'Name',
        'Type',
        'Activity'
    ];

    protected $casts = [
        'gsl_datetime' => 'datetime',
        'Type' => 'integer',
        'Activity' => 'integer'
    ];

    // Relationships
    public function glAccount()
    {
        return $this->belongsTo(FinGl::class, 'GL_id', 'GL_id');
    }

    public function voucherChildren()
    {
        return $this->hasMany(FinVchChld::class, 'GSL_code', 'GSL_code');
    }
}
