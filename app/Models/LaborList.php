<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaborList extends Model
{
    use HasFactory;

    protected $table = 'labor_list';
    protected $primaryKey = 'Labor_ID';
    public $timestamps = true;

    protected $fillable = [
        'Labor',
        'Cate1',
        'Cate2',
        'Cate3',
        'Cate4',
        'Cate5'
    ];

    // Relationships
    public function jobLabors()
    {
        return $this->hasMany(JobcLabor::class, 'Labor', 'Labor');
    }
}
