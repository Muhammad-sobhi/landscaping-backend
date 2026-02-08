<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: #1a202c; 
            margin: 0; 
            padding: 0; 
            line-height: 1.6; 
            background: #f7fafc; 
        }
        .header { 
            background: #4F5D24; 
            color: white; 
            padding: 40px 20px; 
            text-align: center;
        }
        .quote-title { 
            font-size: 24px; 
            font-weight: 200; 
            margin: 10px 0 0 0; 
            letter-spacing: 4px; 
            text-transform: uppercase;
            opacity: 0.9;
        }
        .container { 
            max-width: 600px;
            margin: -30px auto 40px auto;
            padding: 0 20px; 
        }
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            border: 1px solid #e2e8f0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .label-sm { 
            font-size: 10px; 
            text-transform: uppercase; 
            color: #718096; 
            font-weight: 800; 
            letter-spacing: 1.5px; 
            margin-bottom: 8px; 
        }
        .btn {
            display: inline-block;
            padding: 18px 36px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: #4F5D24; 
            color: #ffffff !important;
            margin-top: 20px;
        }
        .stars {
            color: #ecc94b;
            font-size: 24px;
            margin: 15px 0;
        }
        .footer-text {
            text-align: center; 
            margin-top: 40px; 
            font-size: 10px; 
            color: #a0aec0; 
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-size: 22px; font-weight: 900; letter-spacing: -1px;">THAT TREE GUY</div>
        <h1 class="quote-title">Customer Story</h1>
    </div>

    <div class="container">
        <div class="info-card">
            <div class="label-sm">A Personal Invitation</div>
            <div style="font-size: 22px; font-weight: 800; color: #2d3748; margin-bottom: 15px;">
                Hi {{ $lead->full_name }},
            </div>
            
            <p style="color: #4a5568; font-size: 15px;">
                It was a privilege working on your property. We believe every outdoor space has a story, and we would love to feature yours on our new landing page.
            </p>

            <div class="stars">★★★★★</div>

            <p style="color: #718096; font-size: 13px; font-style: italic;">
                "Your feedback helps our small team grow and helps neighbors find tree specialists they can trust."
            </p>

            <a href="{{ $url }}" class="btn">Share My Experience</a>
            
            <div style="margin-top: 30px; font-size: 12px; color: #cbd5e0;">
                Clicking the button will take you to our secure story submission page.
            </div>
        </div>

        <div style="margin-top: 30px; padding: 25px; border-radius: 12px; background: #ffffff; border-left: 4px solid #4F5D24; border: 1px solid #e2e8f0; border-left: 4px solid #4F5D24;">
            <div class="label-sm">Why it matters</div>
            <div style="color: #4a5568; font-size: 13px;">
                By sharing your story, you help us maintain the highest standards of tree care in our community. We truly appreciate your time.
            </div>
        </div>
        
        <div class="footer-text">
            PROFESSIONAL TREE SPECIALISTS • LICENSED & INSURED<br>
            &copy; {{ date('Y') }} THAT TREE GUY
        </div>
    </div>
</body>
</html>