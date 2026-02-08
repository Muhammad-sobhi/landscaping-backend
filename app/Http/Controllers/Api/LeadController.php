<?php

namespace App\Http\Controllers\Api;

use App\Models\Lead;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LeadController extends Controller
{
    
    use AuthorizesRequests;
    public function index()
{
    return Lead::latest()->get(); // Returns a simple array [{}, {}]
}

public function publicCapture(Request $request)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'phone'     => 'required|string|max:20',
        'address'   => 'required|string',
        'notes'     => 'nullable|string',
    ]);

    $lead = \App\Models\Lead::create([
        'full_name' => $validated['full_name'],
        'phone'      => $validated['phone'],
        'address'    => $validated['address'],
        'notes'      => $validated['notes'] ?? 'Lead from Website Landing Page',
        'status'     => 'new', // Ensure your leads table has a 'new' or 'pending' status
    ]);

    return response()->json([
        'message' => 'Lead captured successfully!',
        'lead_id' => $lead->id
    ], 201);
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'     => 'required|string|max:255',
            'email'         => 'nullable|email',
            'phone'         => 'required|string|max:30',
            'city'          => 'required|string|max:100',
            'address'       => 'nullable|string',
            'service_type'  => 'required|string|max:100',
            'description'   => 'nullable|string',
        ]);

        $lead = Lead::create($data);

        return response()->json($lead, 201);
    }

   public function show(Lead $lead)
{
    $this->authorize('view', $lead);
    return $lead;
}

    public function update(Request $request, Lead $lead)
    {
        $lead->update($request->only([
            'status',
            'assigned_to',
        ]));

        return response()->json($lead);
    }
}

