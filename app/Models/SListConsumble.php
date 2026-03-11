<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SListConsumble extends Model
{
    use HasFactory;

    protected $table = 's_list_consumble';
    protected $primaryKey = 'cons_id';
    public $timestamps = true;

    protected $fillable = [
        'consumble',
        'user'
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }
}
