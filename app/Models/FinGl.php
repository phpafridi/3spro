<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinGl extends Model
{
    use HasFactory;

    protected $table = 'fin_gl';
    protected $primaryKey = 'GL_id';
    public $timestamps = true;

    protected $fillable = [
        'ma_id',
        'GL_name',
        'user',
        'datetime',
        'gl_status',
        'rang_start',
        'rang_end',
        'LinkMe',
        'Link',
        'GlCode',
        'End',
        'Name',
        'GGLCodeLink'
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'LinkMe' => 'boolean'
    ];

    // Relationships
    public function mainAccount()
    {
        return $this->belongsTo(FinMainaccount::class, 'ma_id', 'ma_id');
    }

    public function gslAccounts()
    {
        return $this->hasMany(FinGsl::class, 'GL_id', 'GL_id');
    }
}
