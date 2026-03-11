<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRating extends Model
{
    use HasFactory;

    protected $table = 'customer_ratings';
    protected $primaryKey = 'RO';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'RO',
        'datetime',
        'Management',
        'Services',
        'prices',
        'cleanance',
        'behaviour',
        'professionalism',
        'Tech_expertize',
        'commenty_type',
        'message',
        'SA'
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'Management' => 'integer',
        'Services' => 'integer',
        'prices' => 'integer',
        'cleanance' => 'integer',
        'behaviour' => 'integer',
        'professionalism' => 'integer',
        'Tech_expertize' => 'integer'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'RO', 'RO_no');
    }

    public function serviceAdvisor()
    {
        return $this->belongsTo(User::class, 'SA', 'login_id');
    }
}
