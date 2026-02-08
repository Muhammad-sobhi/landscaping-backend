<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'city',
        'address',
        'service_type',
        'description',
        'status',
        'assigned_to',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function offers()
{
    return $this->hasMany(Offer::class);
}
}
