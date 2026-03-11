<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SInsuranceCompany extends Model
{
    use HasFactory;

    protected $table = 's_insurance_companies';
    protected $primaryKey = 'c_id';
    public $timestamps = true;

    protected $fillable = [
        'company_name',
        'contact',
        'email',
        'contact_person',
        'Surveyors_names',
        'Location',
        'addedby',
        'when',
        'status',
        'ntn'
    ];

    protected $casts = [
        'when' => 'date'
    ];

    // Relationships
    public function estimates()
    {
        return $this->hasMany(SEstimate::class, 'insur_company', 'company_name');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'addedby', 'login_id');
    }
}
