<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantCode extends Model
{
    use HasFactory;

    protected $table = 'variant_codes';
    protected $primaryKey = 'variant_id';
    public $timestamps = true;

    protected $fillable = [
        'Variant',
        'Model',
        'Make',
        'Fram',
        'Engine',
        'Category'
    ];

    // Relationships
    public function vehicles()
    {
        return $this->hasMany(VehiclesData::class, 'Variant', 'Variant');
    }
}
