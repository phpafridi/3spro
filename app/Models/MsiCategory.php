<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsiCategory extends Model
{
    use HasFactory;

    protected $table = 'msi_category';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'MSI_CAT',
        'Description',
        'CPUS_Warranty',
        'PM_GM',
        'Labor'
    ];

    // Relationships
    public function jobCards()
    {
        return $this->hasMany(Jobcard::class, 'MSI_cat', 'MSI_CAT');
    }
}
