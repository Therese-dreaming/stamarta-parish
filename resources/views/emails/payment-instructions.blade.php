<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Instructions</title>
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
        .status-acknowledged {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .payment-section {
            background-color: #fff3cd;
            border: 2px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .payment-section h3 {
            color: #856404;
            margin-top: 0;
            font-size: 18px;
        }
        .payment-method {
            background-color: white;
            border-radius: 6px;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #e9ecef;
        }
        .payment-method h4 {
            color: #0d5c2f;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .payment-details {
            margin: 10px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .important-note {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .important-note h4 {
            color: #721c24;
            margin-top: 0;
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
            <h1 class="title">Payment Instructions</h1>
            <p class="subtitle">Sta. Marta Parish</p>
        </div>

        <p>Dear <strong>{{ $booking->user->name }}</strong>,</p>

        <p>Great news! Your booking has been acknowledged and is ready for payment. Please review the payment instructions below.</p>

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
                <span class="label">Total Fee:</span>
                <span class="value">₱{{ number_format($booking->payment->total_fee ?? 0, 2) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="value">
                    <span class="status-badge status-acknowledged">Ready for Payment</span>
                </span>
            </div>
        </div>

        <div class="payment-section">
            <h3>💰 Payment Methods</h3>
            
            <div class="payment-method">
                <h4>🏦 Metrobank</h4>
                <div class="payment-details">
                    <p><strong>Account Name:</strong> Sta. Marta Parish</p>
                    <p><strong>Account Number:</strong> 123-456-789-012</p>
                    <p><strong>Branch:</strong> Hagonoy Branch</p>
                </div>
            </div>
            
            <div class="payment-method">
                <h4>📱 GCash</h4>
                <div class="payment-details">
                    <p><strong>GCash Number:</strong> 0917-123-4567</p>
                    <p><strong>Account Name:</strong> Sta. Marta Parish</p>
                </div>
            </div>
        </div>

        <div class="important-note">
            <h4>⚠️ Important Instructions</h4>
            <ul>
                <li><strong>Reference Number:</strong> Use your Booking ID (#{{ $booking->id }}) as the reference number when making payment.</li>
                <li><strong>Payment Proof:</strong> Take a screenshot or photo of your payment receipt.</li>
                <li><strong>Upload Deadline:</strong> Submit your payment proof within 48 hours of this email.</li>
                <li><strong>Contact Us:</strong> If you encounter any issues, contact us immediately.</li>
            </ul>
        </div>

        <div class="next-steps">
            <h3>📋 Next Steps</h3>
            <ol>
                <li><strong>Make Payment:</strong> Use any of the payment methods above.</li>
                <li><strong>Save Receipt:</strong> Take a screenshot or photo of your payment confirmation.</li>
                <li><strong>Upload Proof:</strong> Log in to your account and upload the payment proof.</li>
                <li><strong>Wait for Verification:</strong> We'll verify your payment within 24 hours.</li>
                <li><strong>Receive Confirmation:</strong> You'll get an email once payment is verified.</li>
            </ol>
        </div>

        <div class="contact-info">
            <p><strong>Need Help?</strong></p>
            <p>If you have any questions about payment, please contact us:</p>
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