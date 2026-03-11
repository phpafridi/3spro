<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SBay extends Model
{
    use HasFactory;

    protected $table = 's_bays';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'bay_name',
        'bay_type',
        'status',
        'category',
        'selection'
    ];

    // Relationships
    public function jobLabors()
    {
        return $this->hasMany(JobcLabor::class, 'bay', 'bay_name');
    }

    public function appointments()
    {
        return $this->hasMany(CrAppointment::class, 'bay', 'bay_name');
    }
}
