<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bookings Report</title>
    <style>
        @page { margin: 20px 10% 20px 10%; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1f2937; }
        
        .header {
            background: linear-gradient(135deg, #0d5c2f 0%, #0a4a26 100%);
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            color: #0d5c2f;
        }
        
        .header p {
            font-size: 10px;
            margin: 0;
            color: #0d5c2f;
            opacity: 0.9;
        }
        
        .meta-info {
            text-align: center;
            margin-bottom: 15px;
            padding: 8px;
            background: #f3f4f6;
            border-radius: 5px;
            font-size: 9px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #0d5c2f;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 9px;
            border: 1px solid #000;
        }
        
        td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
            text-align: center;
        }
        
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-acknowledged { background: #DBEAFE; color: #1E40AF; }
        .status-payment_hold { background: #FED7AA; color: #C2410C; }
        .status-approved { background: #D1FAE5; color: #065F46; }
        .status-completed { background: #D1FAE5; color: #065F46; }
        .status-rejected { background: #FEE2E2; color: #991B1B; }
        .status-cancelled { background: #F3F4F6; color: #4B5563; }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 10px;">
            <img src="{{ public_path('images/church-logo.png') }}" alt="Logo" style="width: 60px; height: 60px; display: inline-block;">
        </div>
        <h1>SANTA MARTA PARISH - BOOKINGS REPORT</h1>
        <p>San Roque</p>
    </div>
    
    <div class="meta-info">
        <strong>Generated:</strong> {{ $generatedAt }}
        @if($filters['date_from'] || $filters['date_to'])
            | <strong>Date Range:</strong> 
            {{ $filters['date_from'] ? \Carbon\Carbon::parse($filters['date_from'])->format('M d, Y') : 'Any' }}
            to
            {{ $filters['date_to'] ? \Carbon\Carbon::parse($filters['date_to'])->format('M d, Y') : 'Any' }}
        @endif
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 7%;">ID</th>
                <th style="width: 15%;">User</th>
                <th style="width: 15%;">Service</th>
                <th style="width: 13%;">Priest</th>
                <th style="width: 10%;">Date</th>
                <th style="width: 8%;">Time</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Payment</th>
                <th style="width: 7%;">Fee</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $booking)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                    <td>{{ $booking->service->name ?? 'N/A' }}</td>
                    <td>{{ $booking->priest->name ?? 'Not Assigned' }}</td>
                    <td>{{ $booking->service_date ? $booking->service_date->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $booking->service_time ?? 'N/A' }}</td>
                    <td>
                        <span class="status status-{{ $booking->status }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                    <td>{{ $booking->payment ? ucfirst($booking->payment->payment_status) : 'No Payment' }}</td>
                    <td>{{ $booking->payment ? '₱' . number_format($booking->payment->total_fee, 2) : '₱0.00' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px;">No bookings found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="text-align: center; font-size: 9px; color: #6b7280; margin-top: 20px;">
        Total Bookings: {{ $bookings->count() }}
    </div>
</body>
</html>
