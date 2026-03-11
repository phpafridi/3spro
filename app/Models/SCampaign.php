<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SCampaign extends Model
{
    use HasFactory;

    protected $table = 's_campaigns';
    protected $primaryKey = 'campaign_id';
    public $timestamps = true;

    protected $fillable = [
        'campaign_name',
        'nature',
        'c_from',
        'c_to',
        'status',
        'user',
        'datetime',
        'LC'
    ];

    protected $casts = [
        'c_from' => 'date',
        'c_to' => 'date',
        'datetime' => 'datetime'
    ];

    // Relationships
    public function labours()
    {
        return $this->hasMany(SCompainghLabour::class, 'compaingh_id', 'campaign_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }
}
