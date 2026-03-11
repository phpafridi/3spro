<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SUploadListname extends Model
{
    use HasFactory;

    protected $table = 's_upload_listname';
    protected $primaryKey = 'list_id';
    public $timestamps = true;

    protected $fillable = [
        'list_name',
        'upload_date',
        'user',
        'status'
    ];

    protected $casts = [
        'upload_date' => 'date'
    ];

    // Relationships
    public function uploadedFrames()
    {
        return $this->hasMany(SUploadedFram::class, 'uploaded_id', 'list_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user', 'login_id');
    }

    public function vinChecks()
    {
        return $this->hasMany(SVinCheck::class, 'listid', 'list_id');
    }
}
