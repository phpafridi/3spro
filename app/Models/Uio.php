<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uio extends Model
{
    use HasFactory;

    protected $table = 'uio';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'UIO_Year',
        'UIO',
        'user',
        'datentime'
    ];

    protected $casts = [
        'datentime' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }
}
