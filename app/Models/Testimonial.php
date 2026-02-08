<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['lead_id', 'content', 'rating', 'token', 'status'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
