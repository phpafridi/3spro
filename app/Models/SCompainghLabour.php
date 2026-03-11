<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SCompainghLabour extends Model
{
    use HasFactory;

    protected $table = 's_compaingh_labour';
    protected $primaryKey = 'cl_id';
    public $timestamps = true;

    protected $fillable = [
        'compaingh_id',
        'labour_des',
        'labour_cost'
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(SCampaign::class, 'compaingh_id', 'campaign_id');
    }
}
