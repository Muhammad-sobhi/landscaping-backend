<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferItem;
use App\Models\Expense;
use App\Models\Job;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ProfessionalQuote;
use Illuminate\Support\Facades\Mail;

class OfferController extends Controller
{
    public function allOffers()
    {
        try {
            // We use .with('lead') to ensure the lead name is included in the JSON
            $offers = Offer::with('lead')->latest()->get();
            return response()->json($offers, 200);
        } catch (\Exception $e) {
            // This will help you see the actual error in your Laravel logs
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $offer = Offer::with(['lead', 'items'])->find($id);

        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }

        return response()->json($offer);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id'             => 'required|exists:leads,id',
            'subtotal'            => 'required|numeric',
            'discount'            => 'nullable|numeric',
            'total'               => 'required|numeric',
            'internal_notes'      => 'nullable|string',
            'message_to_customer' => 'nullable|string',
            'status'              => 'nullable|string',
            // Items validation
            'items'               => 'required|array|min:1',
            'items.*.name'        => 'required|string',
            'items.*.category'    => 'required|string', // material, service, equipment
            'items.*.quantity'    => 'required|numeric',
            'items.*.unit_price'  => 'required|numeric',
            'items.*.total_price' => 'required|numeric',
        ]);

        // Use a Transaction to ensure both Offer and Items save correctly
        return DB::transaction(function () use ($validated) {
            $offer = Offer::create([
                'lead_id'             => $validated['lead_id'],
                'subtotal'            => $validated['subtotal'],
                'discount'            => $validated['discount'] ?? 0,
                'total'               => $validated['total'],
                'status'              => $validated['status'] ?? 'pending',
                'internal_notes'      => $validated['internal_notes'],
                'message_to_customer' => $validated['message_to_customer'],
            ]);

            foreach ($validated['items'] as $item) {
                $offer->items()->create($item);
            }

            return response()->json($offer->load('items'), 201);
        });
    }

    public function convertToJob(Offer $offer)
    {
        if ($offer->status === 'accepted') {
            return response()->json(['message' => 'Offer already converted'], 422);
        }

        return DB::transaction(function () use ($offer) {
            // 1. Create the job
            $job = Job::create([
                'lead_id'        => $offer->lead_id,
                'offer_id'       => $offer->id,
                'price'          => $offer->total,
                'status'         => 'pending',
                'scheduled_date' => now()->format('Y-m-d'),
            ]);

            // 2. Dataflow Logic: Convert "Material" OfferItems into Job Expenses
            // We load items explicitly to ensure we have them
            $offer->load('items');
            
            foreach ($offer->items as $item) {
                if (strtolower($item->category) === 'material') {
                    Expense::create([
                        'job_id'      => $job->id,
                        'category'    => 'Materials',
                        'amount'      => $item->total_price,
                        'description' => "Initial Material: " . $item->name . " (from Quote #" . $offer->id . ")",
                        'spent_at'    => now()->format('Y-m-d'),
                    ]);
                }
            }

            // 3. Update offer status
            $offer->update(['status' => 'accepted']);

            return response()->json([
                'message' => 'Offer converted and materials logged as expenses',
                'job'     => $job
            ], 201);
        });
    }

    public function update(Request $request, Offer $offer)
    {
        if ($offer->status === 'accepted') {
            return response()->json(['message' => 'Accepted offers cannot be modified'], 403);
        }

        $offer->update($request->only([
            'status',
            'message_to_customer',
            'internal_notes',
        ]));

        return response()->json($offer);
    }

 public function sendEmail(Offer $offer)
{
    $offer->load(['lead', 'items']);
    
    // This returns a Collection of all rows in the settings table
    $settings = \App\Models\Setting::all(); 

    Mail::to($offer->lead->email)->send(new ProfessionalQuote($offer, $settings));

    return response()->json(['message' => 'Professional Quote Sent!']);
}
public function downloadPdf(Offer $offer)
    {
        // 1. Load relationships
        $offer->load(['lead', 'items']);

        // 2. Get Settings Collection (same as your email logic)
        $settings = Setting::all();

        // 3. Generate the PDF using the same blade view as your email
        $pdf = Pdf::loadView('emails.quote', compact('offer', 'settings'))
                  ->setPaper('a4', 'portrait');

        // 4. Return for download
        return $pdf->download("Quote_QT-{$offer->id}.pdf");
    }

}