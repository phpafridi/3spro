<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEstLabor extends Model
{
    use HasFactory;

    protected $table = 's_est_labor';
    protected $primaryKey = 'est_lab_id';
    public $timestamps = true;

    protected $fillable = [
        'estm_id',
        'Labor',
        'cost'
    ];

    // Relationships
    public function estimate()
    {
        return $this->belongsTo(SEstimate::class, 'estm_id', 'est_id');
    }
}
