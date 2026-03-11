<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcInvoice extends Model
{
    use HasFactory;

    protected $table = 'jobc_invoice';
    protected $primaryKey = 'Invoice_id';
    public $timestamps = true;

    protected $fillable = [
        'Jobc_id',
        'Labor',
        'Parts',
        'Sublet',
        'Consumble',
        'Ltax',
        'Ptax',
        'Stax',
        'Ctax',
        'Ldiscount',
        'Pdiscount',
        'Sdiscount',
        'Cdiscount',
        'Lnet',
        'Pnet',
        'Snet',
        'Cnet',
        'Total',
        'type',
        'careof',
        'cashier',
        'datetime',
        'Rec_status'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(Jobcard::class, 'Jobc_id', 'Jobc_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier', 'login_id');
    }
}
