<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Approved</title>
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
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        .success-section {
            background-color: #d4edda;
            border: 2px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .success-section h3 {
            color: #155724;
            margin-top: 0;
            font-size: 18px;
        }
        .important-info {
            background-color: #fff3cd;
            border: 2px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .important-info h3 {
            color: #856404;
            margin-top: 0;
            font-size: 18px;
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
            <h1 class="title">Booking Approved!</h1>
            <p class="subtitle">Sta. Marta Parish</p>
        </div>

        <p>Dear <strong>{{ $booking->user->name }}</strong>,</p>

        <p>🎉 Excellent news! Your booking has been approved and your payment has been verified. Your service is now confirmed and we're looking forward to serving you.</p>

        <div class="booking-details">
            <h3 style="color: #0d5c2f; margin-top: 0;">Confirmed Booking Details</h3>
            
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
                    <span class="status-badge status-approved">Approved</span>
                </span>
            </div>
            
            @if($booking->additional_notes)
            <div class="detail-row">
                <span class="label">Additional Notes:</span>
                <span class="value">{{ $booking->additional_notes }}</span>
            </div>
            @endif
        </div>

        <div class="success-section">
            <h3>✅ Payment Verified</h3>
            <p>Your payment of <strong>₱{{ number_format($booking->payment->total_fee, 2) }}</strong> has been successfully verified and processed.</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($booking->payment->payment_method) }}</p>
            <p><strong>Reference Number:</strong> {{ $booking->payment->payment_reference }}</p>
        </div>

        <div class="important-info">
            <h3>📋 Important Information</h3>
            <ul>
                <li><strong>Arrival Time:</strong> Please arrive 15 minutes before your scheduled time.</li>
                <li><strong>Required Documents:</strong> Bring all necessary documents as specified in your booking.</li>
                <li><strong>Dress Code:</strong> Please dress appropriately for the service.</li>
                <li><strong>Contact:</strong> If you need to reschedule, contact us at least 24 hours in advance.</li>
            </ul>
        </div>

        <div class="next-steps">
            <h3>📅 What to Expect</h3>
            <ul>
                <li><strong>Service Day:</strong> Arrive at the parish on {{ $booking->formatted_date }} at {{ $booking->formatted_time }}.</li>
                <li><strong>Check-in:</strong> Present your booking ID (#{{ $booking->id }}) at the parish office.</li>
                <li><strong>Service:</strong> Our staff will guide you through the process.</li>
                <li><strong>Certificate:</strong> You'll receive your certificate after the service.</li>
            </ul>
        </div>

        <div class="contact-info">
            <p><strong>Need to Make Changes?</strong></p>
            <p>If you need to reschedule or have any questions, please contact us:</p>
            <p>📧 Email: parish@stamarta.com<br>
            📞 Phone: (123) 456-7890<br>
            🏠 Address: Sta. Marta Parish, Hagonoy, Bulacan</p>
        </div>

        <div class="footer">
            <p>Thank you for choosing Sta. Marta Parish for your spiritual needs.</p>
            <p>We look forward to serving you!</p>
            <p><small>This is an automated email. Please do not reply to this message.</small></p>
        </div>
    </div>
</body>
</html> 