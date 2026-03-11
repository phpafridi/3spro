<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class STechteam extends Model
{
    use HasFactory;

    protected $table = 's_techteams';
    protected $primaryKey = 'team_id';
    public $timestamps = true;

    protected $fillable = [
        'team_name',
        'members',
        'status',
        'category'
    ];

    // Relationships
    public function jobLabors()
    {
        return $this->hasMany(JobcLabor::class, 'team', 'team_name');
    }
}
