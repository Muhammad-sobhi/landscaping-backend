<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'lead_id',
        'subtotal',
        'discount',
        'total',
        'status',
        'internal_notes',
        'message_to_customer',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function items()
    {
        return $this->hasMany(OfferItem::class);
    }
}

