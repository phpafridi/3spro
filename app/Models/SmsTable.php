<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTable extends Model
{
    use HasFactory;

    protected $table = 'sms_table';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'type',
        'sms_text',
        'edit_on',
        'edit_by'
    ];

    protected $casts = [
        'edit_on' => 'datetime'
    ];

    // Relationships
    public function editor()
    {
        return $this->belongsTo(User::class, 'edit_by', 'login_id');
    }
}
