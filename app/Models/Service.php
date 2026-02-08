<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //// app/Models/Service.php
protected $fillable = ['title', 'description', 'icon', 'tags', 'image_path', 'order'];

protected $casts = [
    'tags' => 'array', // This converts the JSON column to a React-friendly array automatically
];
}
