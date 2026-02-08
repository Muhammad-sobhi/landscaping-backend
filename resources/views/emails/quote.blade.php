<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: #1a202c; 
            margin: 0; 
            padding: 0; 
            line-height: 1.6; 
            background: #ffffff; 
        }
        
        /* Deep Forest Green for a Premium Feel */
        .header { 
            background: #4F5D24; 
            color: white; 
            padding: 60px 60px 80px 60px; 
            position: relative;
        }

        .header-content { display: table; width: 100%; }
        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }

        .quote-title { 
            font-size: 48px; 
            font-weight: 200; 
            margin: 0; 
            letter-spacing: 4px; 
            text-transform: uppercase;
            opacity: 0.9;
        }
        
        .container { 
            padding: 0 60px; 
            margin-top: -50px; /* Overlap effect */
            position: relative;
        }

        /* Modern Info Card */
        .info-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            padding: 40px;
            display: table;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #e2e8f0;
        }

        .info-section { display: table-cell; width: 50%; vertical-align: top; }
        
        .label-sm { 
            font-size: 10px; 
            text-transform: uppercase; 
            color: #718096; 
            font-weight: 800; 
            letter-spacing: 1.5px; 
            margin-bottom: 8px; 
        }

        /* Modern Items Rows */
        .items-table { width: 100%; margin-top: 40px; border-collapse: separate; border-spacing: 0 12px; }
        .items-table th { 
            text-align: left; 
            padding: 0 20px 10px 20px; 
            font-size: 11px; 
            color: #718096; 
            text-transform: uppercase; 
        }
        .item-row td { 
            padding: 20px; 
            background: #f8fafc; 
            border-top: 1px solid #edf2f7;
            border-bottom: 1px solid #edf2f7;
        }
        .item-row td:first-child { border-left: 1px solid #edf2f7; border-radius: 8px 0 0 8px; }
        .item-row td:last-child { border-right: 1px solid #edf2f7; border-radius: 0 8px 8px 0; }

        /* Modern Modern Buttons */
        .actions-bar { 
            margin-top: 50px; 
            text-align: center; 
            padding: 35px; 
            background: #f7faf9; 
            border-radius: 15px;
            border: 1px dashed #cbd5e0;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 13px;
            margin: 0 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-approve { background: #4F5D24; color: white !important; box-shadow: 0 4px 12px rgba(79, 93, 36, 0.3); }
        .btn-query { background: white; color: #4a5568 !important; border: 1px solid #cbd5e0; }

        /* Summary Calculations */
        .summary-container { margin-left: auto; width: 300px; margin-top: 30px; }
        .summary-row { display: table; width: 100%; padding: 8px 0; }
        .summary-label { display: table-cell; color: #718096; font-size: 13px; }
        .summary-value { display: table-cell; text-align: right; font-weight: 700; font-size: 15px; }
        .total-row { border-top: 2px solid #4F5D24; margin-top: 10px; padding-top: 15px !important; }
        .total-price { font-size: 26px; color: #4F5D24; font-weight: 900; }

        .logo-box { background: white; padding: 12px; border-radius: 8px; display: inline-block; }
        .logo-img { max-height: 60px; display: block; }
    </style>
</head>
<body>
    @php
        /* Using your working data logic */
        $logo = \App\Models\Setting::where('key', 'logo_path')->value('value');
        $appName = \App\Models\Setting::where('key', 'project_name')->value('value') ?? 'That Tree Guy';
        $taxRate = (float)(\App\Models\Setting::where('key', 'tax_rate')->value('value') ?? 8.0);
        
        $subtotal = (float)$offer->subtotal;
        $taxAmount = $subtotal * ($taxRate / 100);
        $grandTotal = $subtotal + $taxAmount;
    @endphp

    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo-box">
                    @if($logo)
                        <img src="{{ storage_path('app/public/' . $logo) }}" class="logo-img">
                    @else
                        <h2 style="color: #4F5D24; margin:0;">{{ $appName }}</h2>
                    @endif
                </div>
                <div style="margin-top: 12px; font-size: 11px; letter-spacing: 2px; font-weight: bold; opacity: 0.8;">
                    PROFESSIONAL TREE SPECIALISTS
                </div>
            </div>
            <div class="header-right">
                <h1 class="quote-title">Quote</h1>
                <div style="font-weight: bold; opacity: 0.8; font-size: 18px;">#QT-{{ $offer->id }}</div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="info-card">
            <div class="info-section">
                <div class="label-sm">Prepared For</div>
                <div style="font-size: 20px; font-weight: 800; color: #2d3748;">{{ $offer->lead->full_name }}</div>
                <div style="color: #718096; margin-top: 5px; font-size: 14px;">
                    {{ $offer->lead->address ?? 'Address not specified' }}<br>
                    {{ $offer->lead->phone }}
                </div>
            </div>
            <div class="info-section" style="text-align: right;">
                <div class="label-sm">Date Issued</div>
                <div style="font-weight: 700; color: #2d3748;">{{ $offer->created_at->format('M d, Y') }}</div>
                <div class="label-sm" style="margin-top: 15px;">Status</div>
                <div style="display: inline-block; padding: 5px 15px; background: #fffbeb; color: #92400e; border: 1px solid #fde68a; border-radius: 20px; font-size: 11px; font-weight: 800;">
                    {{ strtoupper($offer->status) }}
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Service / Description</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offer->items as $item)
                <tr class="item-row">
                    <td>
                        <div style="font-weight: 700; font-size: 15px; color: #2d3748;">{{ $item->name }}</div>
                        <div style="font-size: 11px; color: #718096;">Category: {{ ucfirst($item->category) }}</div>
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #4a5568;">{{ $item->quantity }}</td>
                    <td style="text-align: right; font-weight: 800; color: #2d3748;">
                        ${{ number_format($item->total_price, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-container">
            <div class="summary-row">
                <div class="summary-label">Subtotal</div>
                <div class="summary-value">${{ number_format($subtotal, 2) }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Tax ({{ $taxRate }}%)</div>
                <div class="summary-value">${{ number_format($taxAmount, 2) }}</div>
            </div>
            <div class="summary-row total-row">
                <div class="summary-label" style="font-weight: 800; color: #2d3748;">Estimated Total</div>
                <div class="summary-value total-price">${{ number_format($grandTotal, 2) }}</div>
            </div>
        </div>

        <div class="actions-bar">
            <div class="label-sm" style="margin-bottom: 15px;">Ready to proceed with this quote?</div>
            <a href="{{ url('/offers/'.$offer->id.'/approve') }}" class="btn btn-approve">✔ Approve & Schedule</a>
            <a href="mailto:office@thattreeguy.com?subject=Question regarding Quote #QT-{{ $offer->id }}" class="btn btn-query">✉ Ask a Question</a>
        </div>

        <div style="margin-top: 40px; padding: 20px; border-radius: 8px; background: #f8fafc; border-left: 4px solid #4F5D24;">
            <div class="label-sm">Note to Customer</div>
            <div style="font-style: italic; color: #4a5568; font-size: 13px;">
                "{{ $offer->message_to_customer ?? 'This quote is valid for 30 days. Please reach out if you have any questions.' }}"
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 50px; font-size: 10px; color: #a0aec0; letter-spacing: 2px;">
            {{ strtoupper($appName) }} SPECIALISTS • LICENSED & INSURED
        </div>
    </div>
</body>
</html>