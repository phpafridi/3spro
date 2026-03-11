<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSaleInv extends Model
{
    use HasFactory;

    protected $table = 'p_sale_inv';
    protected $primaryKey = 'sale_inv';
    public $timestamps = true;

    protected $fillable = [
        'Jobber',
        'payment_method',
        'discount',
        'tax',
        'remarks',
        'Total_amount',
        'user',
        'datetime',
        'status'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function jobber()
    {
        return $this->belongsTo(PJobber::class, 'Jobber', 'jbr_name');
    }

    public function saleParts()
    {
        return $this->hasMany(PSalePart::class, 'sale_inv', 'sale_inv');
    }

    public function returns()
    {
        return $this->hasMany(PSaleReturn::class, 'invoice_no', 'sale_inv');
    }
}
