<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
class InvoiceController extends Controller
{
    public function index()
{
    // Eager load 'lead' so we can show the customer name in the table
    return response()->json(Invoice::with('lead')->latest()->get());
}
   public function store(Job $job)
{
    $job->load('employees'); // Uses your existing relationship
    
    // Pull the tax rate from the settings table (defaults to 0 if not found)
    $taxRateValue = Setting::where('key', 'tax_rate')->value('value') ?? 0;
    
    $subtotal = $job->price;
    $taxAmount = $subtotal * ($taxRateValue / 100);

    $invoice = Invoice::create([
        'job_id' => $job->id,
        'lead_id' => $job->lead_id,
        'invoice_number' => 'INV-' . now()->timestamp,
        'subtotal' => $subtotal,
        'tax' => $taxAmount,
        'total' => $subtotal + $taxAmount,
        'status' => 'pending',
        'issued_at' => now(),
    ]);

    return response()->json($invoice, 201);
}

    public function pay(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method ?? 'cash',
            'paid_at' => now(),
        ]);

        return response()->json($invoice);
    }
    public function download(Invoice $invoice)
{
    // Load the relationships needed for the document
    $invoice->load(['lead', 'job']);

    // This refers to a file at resources/views/invoices/pdf.blade.php
    $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

    return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
}
}

