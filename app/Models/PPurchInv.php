<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPurchInv extends Model
{
    use HasFactory;

    protected $table = 'p_purch_inv';
    protected $primaryKey = 'Invoice_no';
    public $timestamps = true;

    protected $fillable = [
        'jobber',
        'Invoice_number',
        'payment_method',
        'Purchase_Requis',
        'Total_amount',
        'user',
        'mdate',
        'date',
        'deleverynote',
        'consignmentnote',
        'Receivername',
        'status'
    ];

    protected $casts = [
        'mdate' => 'date',
        'date' => 'datetime'
    ];

    // Relationships
    public function jobber()
    {
        return $this->belongsTo(PJobber::class, 'jobber', 'jbr_name');
    }

    public function stockItems()
    {
        return $this->hasMany(PPurchStock::class, 'Invoice_no', 'Invoice_no');
    }

    public function returns()
    {
        return $this->hasMany(PPurchReturn::class, 'invoice_no', 'Invoice_no');
    }
}
