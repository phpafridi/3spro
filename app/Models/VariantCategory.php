<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantCategory extends Model
{
    use HasFactory;

    protected $table = 'variant_category';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'dsf',
        'saf',
        'saffd',
        'sdaf',
        'sfd'
    ];
}
