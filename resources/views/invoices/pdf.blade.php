<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', sans-serif; color: #2d3748; margin: 0; padding: 0; line-height: 1.5; background: #fff; }
        
        /* Matching the Logo's Green Background */
        .header { 
            background: #688020; 
            color: white; 
            padding: 50px 60px; 
            position: relative;
        }

        /* Subtle bottom border to separate from body */
        .header-accent {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: rgba(0,0,0,0.1);
        }
        
        .container { padding: 40px 60px; }
        table { width: 100%; border-collapse: collapse; }
        .text-right { text-align: right; }
        .text-white { color: white !important; }
        
        /* Labels & Values */
        .label { font-size: 10px; text-transform: uppercase; color: #718096; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 4px; }
        .header .label { color: rgba(255,255,255,0.7); } 
        .value { font-size: 14px; font-weight: bold; }
        
        /* Items Table */
        .items-table { margin-top: 40px; }
        .items-table th { 
            background: #f8fafc; 
            padding: 12px 15px; 
            font-size: 11px; 
            text-transform: uppercase; 
            color: #4a5568; 
            text-align: left; 
            border-bottom: 2px solid #e2e8f0; 
        }
        .items-table td { padding: 18px 15px; border-bottom: 1px solid #edf2f7; font-size: 13px; }
        
        /* Summary Box */
        .summary-wrapper { width: 100%; margin-top: 30px; }
        .summary-box { width: 280px; margin-left: auto; }
        .summary-box td { padding: 8px 0; font-size: 14px; }
        .total-label { font-size: 18px; font-weight: 900; color: #708238; }
        .total-amount { font-size: 24px; font-weight: 900; color: #708238; }
        
        /* Status Badge */
        .badge { 
            padding: 6px 14px; 
            border-radius: 4px; 
            font-size: 11px; 
            font-weight: 900; 
            display: inline-block; 
            text-transform: uppercase; 
        }
        .paid { background: #d1fae5; color: #065f46; border: 1px solid #34d399; }
        .pending { background: #fffbeb; color: #92400e; border: 1px solid #fbbf24; }
        
        /* Logo Container to ensure it looks crisp */
        .logo-box {
    padding: 0;
    background: transparent;
}

        .logo-img { max-height: 65px; display: block; }
    </style>
</head>
<body>
    @php
        $job = $invoice->job;
        $billableExpenses = $job->expenses->filter(function($exp) {
            return in_array(strtolower($exp->category), ['materials', 'rental']);
        });
        
        $expenseTotal = $billableExpenses->sum('amount');
        $servicePrice = (float)$job->price;
        $calcSubtotal = $servicePrice + $expenseTotal;
        $taxRateRaw = (float)(\App\Models\Setting::where('key', 'tax_rate')->value('value') ?? 8);
        $calcTax = $calcSubtotal * ($taxRateRaw / 100);
        $calcTotal = $calcSubtotal + $calcTax;

        $logo = \App\Models\Setting::where('key', 'logo_path')->value('value');
        $appName = \App\Models\Setting::where('key', 'project_name')->value('value') ?? config('app.name');
    @endphp

    <div class="header">
        <table>
            <tr>
                <td width="60%">
                    <div class="logo-box">
                        @if($logo)
                            <img src="{{ storage_path('app/public/' . $logo) }}" class="logo-img">
                        @else
                            <h1 style="color: #708238; margin: 0; font-size: 24px;">{{ $appName }}</h1>
                        @endif
                    </div>
                    <div style="margin-top: 15px; color: white; font-size: 12px; letter-spacing: 1px; font-weight: bold;">
                        THAT TREE GUY SPECIALISTS
                    </div>
                </td>
                <td class="text-right" width="40%">
                    <h1 style="margin: 0; font-size: 45px; color: white; letter-spacing: 2px; font-weight: 200; opacity: 0.9;">INVOICE</h1>
                    <div class="text-white" style="font-size: 16px; margin-bottom: 5px; font-weight: bold;">#{{ $invoice->invoice_number }}</div>
                    <div class="label" style="color: rgba(255,255,255,0.8);">Date: {{ $invoice->created_at->format('M d, Y') }}</div>
                </td>
            </tr>
        </table>
        <div class="header-accent"></div>
    </div>

    <div class="container">
        <table style="margin-bottom: 40px;">
            <tr>
                <td width="55%" style="vertical-align: top;">
                    <div class="label">Billed To</div>
                    <div class="value" style="font-size: 20px; color: #1a202c;">{{ $invoice->lead->full_name }}</div>
                    <div style="font-size: 13px; color: #4a5568; margin-top: 5px;">
                        {{ $invoice->lead->address }}<br>
                        {{ $invoice->lead->phone }}
                    </div>
                </td>
                <td width="45%" class="text-right" style="vertical-align: top;">
                    <div class="label">Payment Status</div>
                    <div class="badge {{ $invoice->status === 'paid' ? 'paid' : 'pending' }}">
                        {{ $invoice->status }}
                    </div>
                    <div style="margin-top: 15px;">
                        <div class="label">Method</div>
                        <div class="value">{{ strtoupper($invoice->payment_method ?? 'Not Specified') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="70%">Description</th>
                    <th width="30%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="value">Professional Services: {{ $job->notes ?: 'Tree Care Project' }}</div>
                        <div style="font-size: 11px; color: #718096; margin-top: 4px;">Service Date: {{ $job->scheduled_date }}</div>
                    </td>
                    <td class="text-right value">${{ number_format($servicePrice, 2) }}</td>
                </tr>

                @foreach($billableExpenses as $exp)
                <tr>
                    <td>
                        <div class="value">{{ ucfirst($exp->category) }} Item</div>
                        <div style="font-size: 11px; color: #718096; margin-top: 4px;">{{ $exp->description ?: 'Additional project requirement' }}</div>
                    </td>
                    <td class="text-right value">${{ number_format($exp->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-wrapper">
            <table class="summary-box">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="text-right value">${{ number_format($calcSubtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Tax ({{ $taxRateRaw }}%)</td>
                    <td class="text-right value">${{ number_format($calcTax, 2) }}</td>
                </tr>
                <tr class="grand-total-row">
                    <td class="total-label" style="padding-top: 15px;">Total Due</td>
                    <td class="text-right total-amount" style="padding-top: 15px;">${{ number_format($calcTotal, 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 80px; padding-top: 20px; border-top: 1px solid #edf2f7;">
            <table>
                <tr>
                    <td>
                        <div class="label">Notes</div>
                        <div style="font-size: 12px; color: #718096; line-height: 1.6;">
                            Thank you for your business. Please include the invoice number on your payment.
                        </div>
                    </td>
                </tr>
            </table>
            <div style="margin-top: 70px;">
    <table width="100%">
        <tr>
            <td width="50%">
                <div class="label">Customer Signature</div>
                <div style="margin-top: 40px; border-bottom: 1px solid #4a5568; width: 90%;"></div>
                <div style="font-size: 11px; color: #718096; margin-top: 6px;">
                    Signature or Printed Name
                </div>
            </td>
            <td width="50%" class="text-right">
                <div class="label">Date</div>
                <div style="margin-top: 40px; border-bottom: 1px solid #4a5568; width: 60%; margin-left: auto;"></div>
            </td>
        </tr>
    </table>
</div>

        </div>
    </div>
</body>
</html>