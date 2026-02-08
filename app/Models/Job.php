<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'lead_id',
        'offer_id',
        'status',
        'scheduled_date',
        'completed_date',
        'price', // <--- MUST BE HERE
        'notes',
        'before_photo_path',
        'after_photo_path'
    ];
    

    public function offer() { return $this->belongsTo(Offer::class); }
    public function lead() { return $this->belongsTo(Lead::class); }
    public function expenses()
{
    // Assuming your expenses table has a 'job_id' column
    return $this->hasMany(Expense::class);
}
   
    public function employees()
{
    return $this->belongsToMany(User::class, 'job_user', 'job_id', 'user_id');
}

public function invoice()
{
    return $this->hasOne(Invoice::class);
}

public function client()
{
    return $this->belongsTo(Client::class, 'lead_id', 'id');
}

protected $appends = ['before_photo_url', 'after_photo_url'];

public function getBeforePhotoUrlAttribute()
{
    return $this->before_photo_path ? asset('storage/' . $this->before_photo_path) : null;
}

public function getAfterPhotoUrlAttribute()
{
    return $this->after_photo_path ? asset('storage/' . $this->after_photo_path) : null;
}
}

