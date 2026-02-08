<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    // Add this block
    protected $fillable = [
        'job_id',
        'category',
        'amount',
        'description',
        'spent_at'
    ];
    public function job()
{
    return $this->belongsTo(Job::class);
}
}