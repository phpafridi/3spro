<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMsiCategory extends Model
{
    use HasFactory;

    protected $table = 's_msi_categories';
    protected $primaryKey = 'msi_id';
    public $timestamps = true;

    protected $fillable = [
        'MSI',
        'ro_type',
        'service_nature',
        'Description',
        'CPUS_Warranty'
    ];

    // Relationships
    public function jobCards()
    {
        return $this->hasMany(Jobcard::class, 'MSI_cat', 'MSI');
    }
}
