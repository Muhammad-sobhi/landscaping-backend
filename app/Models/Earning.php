<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $fillable = [
        'user_id',
        'job_id',
        'type',
        'amount',
        'earned_at',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function job() { return $this->belongsTo(Job::class); }
    protected $casts = [
    'earned_at' => 'datetime',
    'amount'    => 'float',
];
}

