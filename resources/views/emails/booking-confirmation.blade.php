<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #0d5c2f;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: 80px;
            background-color: #0d5c2f;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 15px;
        }
        .logo i {
            color: white;
            font-size: 40px;
            line-height: 80px;
        }
        .title {
            color: #0d5c2f;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
            margin: 5px 0 0 0;
        }
        .booking-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .next-steps {
            background-color: #e7f3ff;
            border-left: 4px solid #0d5c2f;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .next-steps h3 {
            color: #0d5c2f;
            margin-top: 0;
            font-size: 18px;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <i>⛪</i>
            </div>
            <h1 class="title">Booking Confirmation</h1>
            <p class="subtitle">Sta. Marta Parish</p>
        </div>

        <p>Dear <strong>{{ $booking->user->name }}</strong>,</p>

        <p>Thank you for booking a service with Sta. Marta Parish. Your booking has been successfully submitted and is currently under review.</p>

        <div class="booking-details">
            <h3 style="color: #0d5c2f; margin-top: 0;">Booking Details</h3>
            
            <div class="detail-row">
                <span class="label">Booking ID:</span>
                <span class="value">#{{ $booking->id }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Service:</span>
                <span class="value">{{ $booking->service->name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Date:</span>
                <span class="value">{{ $booking->formatted_date }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Time:</span>
                <span class="value">{{ $booking->formatted_time }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Contact Phone:</span>
                <span class="value">{{ $booking->contact_phone }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="value">
                    <span class="status-badge status-pending">Pending Review</span>
                </span>
            </div>
            
            @if($booking->additional_notes)
            <div class="detail-row">
                <span class="label">Additional Notes:</span>
                <span class="value">{{ $booking->additional_notes }}</span>
            </div>
            @endif
        </div>

        <div class="next-steps">
            <h3>What Happens Next?</h3>
            <ul>
                <li><strong>Review Process:</strong> Our parish staff will review your booking within 24-48 hours.</li>
                <li><strong>Payment Instructions:</strong> Once acknowledged, you'll receive payment instructions via email.</li>
                <li><strong>Document Submission:</strong> You may be required to submit additional documents.</li>
                <li><strong>Final Confirmation:</strong> You'll receive final confirmation once everything is approved.</li>
            </ul>
        </div>

        <div class="contact-info">
            <p><strong>Need Help?</strong></p>
            <p>If you have any questions about your booking, please contact us:</p>
            <p>📧 Email: parish@stamarta.com<br>
            📞 Phone: (123) 456-7890<br>
            🏠 Address: Sta. Marta Parish, Hagonoy, Bulacan</p>
        </div>

        <div class="footer">
            <p>Thank you for choosing Sta. Marta Parish for your spiritual needs.</p>
            <p><small>This is an automated email. Please do not reply to this message.</small></p>
        </div>
    </div>
</body>
</html> 