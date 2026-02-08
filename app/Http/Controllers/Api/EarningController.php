<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Earning;

class EarningController extends Controller
{
    public function index()
{
    // For Super Admin
    return Earning::with('user')->latest()->paginate(20);
}

public function myEarnings(Request $request)
{
    // For Employees
    return $request->user()->earnings()->latest()->paginate(20);
}

   public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'nullable|exists:jobs,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'earned_at' => 'required',
        ]);

        // Fix: Convert ISO 8601 (2026-01-20T21:12:18.434Z) 
        // to MySQL format (2026-01-20 21:12:18)
        $data['earned_at'] = date('Y-m-d H:i:s', strtotime($request->earned_at));

        $earning = Earning::create($data);

        return response()->json($earning, 201);
    }
}

