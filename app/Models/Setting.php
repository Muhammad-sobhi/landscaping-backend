<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // This allows us to use Setting::updateOrCreate
    protected $fillable = ['key', 'value'];
}