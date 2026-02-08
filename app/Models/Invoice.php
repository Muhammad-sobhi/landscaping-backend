<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'job_id',
        'lead_id',
        'invoice_number',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'status',
        'issued_at',
        'paid_at',
    ];
protected $casts = [
    'issued_at' => 'datetime',
    'paid_at' => 'datetime',
];
    public function job() { return $this->belongsTo(Job::class); }
    public function lead() { return $this->belongsTo(Lead::class); }
}
