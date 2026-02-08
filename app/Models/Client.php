<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'city',
        'address',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
