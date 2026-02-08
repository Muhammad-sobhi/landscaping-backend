<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobEmployeeController extends Controller
{
    public function assign(Request $request, Job $job)
    {
        $request->validate([
            'employee_ids' => 'required|array'
        ]);

        $job->employees()->sync($request->employee_ids);

        return response()->json(['message' => 'Employees assigned']);
    }
}
