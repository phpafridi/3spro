<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPart extends Model
{
    use HasFactory;

    protected $table = 'p_parts';
    protected $primaryKey = 'part_id';
    public $timestamps = true;

    protected $fillable = [
        'Part_no',
        'Description',
        'Location',
        'catetype',
        'part_type',
        'Model',
        'ReOrder',
        'user',
        'datetime'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function stock()
    {
        return $this->hasMany(PPurchStock::class, 'part_no', 'Part_no');
    }

    public function subCategories()
    {
        return $this->hasMany(PPartsSubcat::class, 'partnumber', 'Part_no');
    }

    public function jobParts()
    {
        return $this->hasMany(JobcPart::class, 'part_number', 'Part_no');
    }
}
