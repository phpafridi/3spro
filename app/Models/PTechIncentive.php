<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTechIncentive extends Model
{
    use HasFactory;

    protected $table = 'p_tech_incentive';
    protected $primaryKey = 'inc_id';
    public $timestamps = true;

    protected $fillable = [
        'wp_or_wc',
        'wp_wc_id',
        'amount',
        'user',
        'datetime'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }
}
