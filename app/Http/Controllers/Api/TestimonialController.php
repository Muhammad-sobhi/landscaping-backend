<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Lead;
use App\Mail\RequestStoryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class TestimonialController extends Controller
{
    // Dashboard: List all testimonials (Pending & Approved)
    public function index()
    {
        return Testimonial::with('lead')->orderBy('created_at', 'desc')->get();
    }
    // 1. Triggered from your Dashboard
    public function sendRequest(Lead $lead)
    {
        // Generate a unique token for this specific request
        $token = Str::random(40);

        // Create a placeholder record so the token is valid
        Testimonial::create([
            'lead_id' => $lead->id,
            'token' => $token,
            'content' => '', // Empty until they submit
        ]);
        $url = config('app.landing_page_url') . "/share-story?token=" . $token;

        Mail::to($lead->email)->send(new RequestStoryMail($lead, $token));

        return response()->json(['message' => 'Email sent successfully']);
    }

    // 2. Public Route: Used by the customer (No Auth needed)
    public function submitStory(Request $request)
    {
        $request->validate([
            'token' => 'required|exists:testimonials,token',
            'content' => 'required|min:10',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $testimonial = Testimonial::where('token', $request->token)->firstOrFail();
        
        // Error Prevention: Don't let them submit twice with the same link
        if ($testimonial->content !== '') {
            return response()->json(['message' => 'This story has already been submitted.'], 400);
        }

        $testimonial->update([
            'content' => $request->content,
            'rating' => $request->rating,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Story submitted!']);
    }

    public function approve($id)
{
    // Find the record or fail with 404
    $testimonial = Testimonial::findOrFail($id);
    
    // Safety check: Prevent approving empty requests
    if (empty($testimonial->content)) {
        return response()->json([
            'message' => 'Cannot approve an empty testimonial. Awaiting customer response.'
        ], 400);
    }

    $testimonial->update([
        'status' => 'approved'
    ]);

    return response()->json([
        'message' => 'Story approved successfully!',
        'testimonial' => $testimonial
    ]);
}

    // 3. Admin Route: Approve the story
    public function getApproved() {
        return Testimonial::with('lead')
            ->where('status', 'approved')
            ->whereNotNull('content')
            ->latest()
            ->get();
    }

    // Dashboard: Delete/Reject
    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();
        return response()->json(['message' => 'Story removed']);
    }
}
