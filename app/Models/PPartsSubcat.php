<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPartsSubcat extends Model
{
    use HasFactory;

    protected $table = 'p_parts_subcat';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'category',
        'subcategory',
        'partnumber'
    ];

    // Relationships
    public function part()
    {
        return $this->belongsTo(PPart::class, 'partnumber', 'Part_no');
    }
}
