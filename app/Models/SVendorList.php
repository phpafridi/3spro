<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SVendorList extends Model
{
    use HasFactory;

    protected $table = 's_vendor_list';
    protected $primaryKey = 'v_id';
    public $timestamps = true;

    protected $fillable = [
        'vendor_name',
        'work_type',
        'contact',
        'contact_person',
        'Location',
        'addedby',
        'when',
        'status'
    ];

    protected $casts = [
        'when' => 'date'
    ];

    // Relationships
    public function jobSublets()
    {
        return $this->hasMany(JobcSublet::class, 'Vendor', 'vendor_name');
    }

    public function estimateSublets()
    {
        return $this->hasMany(SEstSublet::class, 'Sublet', 'vendor_name');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'addedby', 'login_id');
    }
}
