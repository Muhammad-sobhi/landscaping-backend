<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferItem extends Model
{
    protected $fillable = ['offer_id', 'name', 'category', 'quantity', 'unit_price', 'total_price'];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}