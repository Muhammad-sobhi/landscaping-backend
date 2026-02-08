<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Offer;

class JobController extends Controller
{
 public function index(Request $request)
{
    $user = $request->user();

    if ($user->role === 'employee' || $user->role === 'technician') {
        return Job::whereHas('employees', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['lead', 'invoice']) // Load invoice so they can see payment status
        ->latest()
        ->get();
    }

    return Job::with(['lead', 'employees', 'invoice'])->latest()->get();
}

   public function show($id) {
    // We now include 'expenses' so JobDetails can calculate net profit
    return Job::with(['lead', 'invoice', 'employees', 'expenses'])->findOrFail($id);
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'lead_id' => 'required|exists:leads,id',
        'price' => 'nullable|numeric',
        'scheduled_date' => 'nullable|date',
        'notes' => 'nullable|string',
        'offer_id' => 'nullable|exists:offers,id',
        'employee_id' => 'nullable|exists:users,id' // The tech assignment
    ]);

    // Create the job (offer_id can be null now)
    $job = Job::create([
        'lead_id'        => $validated['lead_id'],
        'offer_id'       => $request->offer_id ?? null,
        'price'          => $validated['price'] ?? 0,
        'scheduled_date' => $validated['scheduled_date'] ?? now(),
        'notes'          => $validated['notes'] ?? '',
        'status'         => 'pending',
    ]);

    // Professional Assignment: Attach the employee to the job
    if ($request->filled('employee_id')) {
        // If using a many-to-many relationship (pivot table)
        $job->employees()->attach($request->employee_id);
    }

    return response()->json($job->load(['lead', 'employees']), 201);
}

   // JobController.php

// ... inside your update method ...

public function update(Request $request, Job $job)
{
    $oldStatus = $job->status;
    
    // 1. Update the Job fields
    $job->update($request->only(['price', 'status', 'scheduled_date', 'notes']));

    // 2. NEW: Automatic Invoice Logic
    if ($request->status === 'completed' && !$job->invoice()->exists()) {
        $taxRateValue = \App\Models\Setting::where('key', 'tax_rate')->value('value') ?? 0;
        $subtotal = $job->price;
        $taxAmount = $subtotal * ($taxRateValue / 100);

        $job->invoice()->create([
            'lead_id' => $job->lead_id,
            'invoice_number' => 'INV-' . now()->timestamp,
            'subtotal' => $subtotal,
            'tax' => $taxAmount,
            'total' => $subtotal + $taxAmount,
            'status' => 'pending',
            'issued_at' => now(),
        ]);
    }

    // 3. NEW: Recording Earnings for Employees
    // Trigger this when job is marked 'completed' and it wasn't completed before
    if ($request->status === 'completed' && $oldStatus !== 'completed') {
        $employees = $job->employees;
        
        // Calculate commission (e.g., 10% of job price, or use a setting)
        // Adjust this formula to match your business logic
        $commissionRate = 0.10; 
        $commissionPerPerson = ($job->price * $commissionRate) / (count($employees) ?: 1);

        foreach ($employees as $employee) {
            // Check if we already recorded this to avoid duplicates
            $exists = \App\Models\Earning::where('user_id', $employee->id)
                                        ->where('job_id', $job->id)
                                        ->exists();
            
            if (!$exists && $commissionPerPerson > 0) {
                \App\Models\Earning::create([
                    'user_id'   => $employee->id,
                    'job_id'    => $job->id,
                    'amount'    => $commissionPerPerson,
                    'type'      => 'Project Commission',
                    'earned_at' => now(),
                ]);
            }
        }
    }

    // 4. Update the Assignment
    if ($request->has('employee_id')) {
        $employeeId = $request->employee_id;
        $job->employees()->sync($employeeId ? [$employeeId] : []);
    }

    return response()->json($job->load(['lead', 'employees', 'invoice']));
}

    public function uploadPhotos(Request $request, Job $job) 
    {
        $request->validate([
            'before_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'after_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('before_photo')) {
            $job->before_photo_path = $request->file('before_photo')->store('jobs/before', 'public');
        }
        
        if ($request->hasFile('after_photo')) {
            $job->after_photo_path = $request->file('after_photo')->store('jobs/after', 'public');
        }

        $job->save();

        return response()->json([
            'message' => 'Photos uploaded successfully',
            'job' => $job->load('lead')
        ]);
    }

  // app/Http/Controllers/JobController.php

public function assignCrew(Request $request, $id)
{
    try {
        $job = Job::findOrFail($id);
        
        // This is the magic line that saves to the database
        $job->employees()->toggle($request->user_id);

        return response()->json([
            'status' => 'success',
            'crew' => $job->load('employees')->employees
        ]);
    } catch (\Exception $e) {
        // This will show up in your F12 Network tab response
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function destroy(Job $job) 
{
    // Ensure the user is actually logged in first
    $user = auth('sanctum')->user();

    if (!$user || $user->role !== 'admin') {
        return response()->json([
            'message' => 'Unauthorized. Only admins can delete projects.'
        ], 403);
    }

    $job->delete();
    
    return response()->json([
        'message' => 'Project deleted successfully'
    ]);
}
}