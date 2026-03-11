<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SNewPart extends Model
{
    use HasFactory;

    protected $table = 's_new_parts';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'Description',
        'User'
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'User', 'login_id');
    }
}
