<?php

// app/Models/PortfolioItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    protected $fillable = ['title', 'category', 'image_path', 'is_featured'];

    // Ensuring the boolean is treated correctly
    protected $casts = [
        'is_featured' => 'boolean',
    ];
}