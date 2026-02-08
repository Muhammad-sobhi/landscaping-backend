<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            // Last 5 jobs added
            'recent_jobs' => Job::with('lead')->latest()->take(5)->get(),
            
            // Jobs scheduled for today
            'todays_schedule' => Job::with('lead')
                ->whereDate('scheduled_date', Carbon::today())
                ->get(),
                
            // Jobs with a specific status that need attention
            'todo_list' => Job::whereIn('status', ['pending', 'assigned'])->count()
        ]);
    }
}