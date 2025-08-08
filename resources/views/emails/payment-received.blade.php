<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Received</title>
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
        .status-payment-hold {
            background-color: #fff3cd;
            color: #856404;
        }
        .payment-details {
            background-color: #d4edda;
            border: 2px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .payment-details h3 {
            color: #155724;
            margin-top: 0;
            font-size: 18px;
        }
        .payment-info {
            background-color: white;
            border-radius: 6px;
            padding: 15px;
            margin: 10px 0;
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
            <h1 class="title">Payment Received</h1>
            <p class="subtitle">Sta. Marta Parish</p>
        </div>

        <p>Dear <strong>{{ $booking->user->name }}</strong>,</p>

        <p>Thank you! We have received your payment proof for your booking. Our staff will review and verify your payment within 24 hours.</p>

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
                <span class="label">Status:</span>
                <span class="value">
                    <span class="status-badge status-payment-hold">Payment Under Review</span>
                </span>
            </div>
        </div>

        <div class="payment-details">
            <h3>💰 Payment Information</h3>
            
            <div class="payment-info">
                <p><strong>Payment Method:</strong> {{ ucfirst($booking->payment->payment_method) }}</p>
                <p><strong>Reference Number:</strong> {{ $booking->payment->payment_reference }}</p>
                <p><strong>Amount Paid:</strong> ₱{{ number_format($booking->payment->total_fee, 2) }}</p>
                <p><strong>Submitted:</strong> {{ $booking->payment->payment_submitted_at->format('M d, Y g:i A') }}</p>
                @if($booking->payment->payment_notes)
                <p><strong>Notes:</strong> {{ $booking->payment->payment_notes }}</p>
                @endif
            </div>
        </div>

        <div class="next-steps">
            <h3>📋 What Happens Next?</h3>
            <ul>
                <li><strong>Payment Verification:</strong> Our staff will verify your payment proof within 24 hours.</li>
                <li><strong>Email Notification:</strong> You'll receive an email once payment is verified.</li>
                <li><strong>Booking Confirmation:</strong> Once approved, your booking will be confirmed.</li>
                <li><strong>Service Preparation:</strong> We'll prepare everything for your scheduled service.</li>
            </ul>
        </div>

        <div class="contact-info">
            <p><strong>Need Help?</strong></p>
            <p>If you have any questions about your payment or booking, please contact us:</p>
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