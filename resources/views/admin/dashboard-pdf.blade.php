<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1f2937;
            padding: 0 60px;
        }
        
        @page {
            margin: 20px 0;
        }
        
        .page-header {
            background: linear-gradient(135deg, #0d5c2f 0%, #0a4a26 100%);
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-logo {
            width: 50px;
            height: 50px;
        }
        
        .page-header h1 {
            font-size: 24px;
            margin: 0;
            color: #0d5c2f;
        }
        
        .page-header p {
            font-size: 12px;
            opacity: 0.9;
            color: #0d5c2f;
            margin-top: 5px;
        }
        
        .section-page-header {
            background: #0d5c2f;
            color: white;
            padding: 8px 0;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .meta-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f3f4f6;
            border-radius: 5px;
        }
        
        .meta-info p {
            font-size: 10px;
            color: #6b7280;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .stat-card .label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .stat-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #0d5c2f;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-page-header {
            background: #0d5c2f;
            color: white;
            padding: 10px 0;
            text-align: center;
            font-size: 10px;
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0d5c2f;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #0d5c2f;
        }
        
        .chart-container {
            margin: 15px 0;
            text-align: center;
            page-break-inside: avoid;
        }
        
        .chart-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
        }
        
        .chart-title {
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .two-column {
            display: table;
            width: 100%;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
        }
        
        .column:first-child {
            padding-left: 0;
        }
        
        .column:last-child {
            padding-right: 0;
        }
    </style>
</head>
<body>
    <!-- Main Header (First Page) -->
    <div class="page-header">
        <div class="header-content">
            <img src="{{ public_path('images/church-logo.png') }}" alt="Logo" class="header-logo">
            <div>
                <h1>Santa Marta Parish</h1>
                <p>San Roque</p>
            </div>
        </div>
    </div>
    
    <!-- Meta Information -->
    <div class="meta-info">
        <p><strong>Generated:</strong> {{ $generatedAt }}</p>
        <p><strong>Report Type:</strong> {{ $activeTab === 'all' ? 'Complete Dashboard Report' : ucfirst($activeTab) . ' Analytics' }}</p>
    </div>
    
    <!-- Key Statistics -->
    <div class="section">
        <div class="section-title">Key Performance Indicators</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-card">
                    <div class="label">Total Bookings</div>
                    <div class="value">{{ $stats['total_bookings'] ?? 0 }}</div>
                </div>
                <div class="stat-card">
                    <div class="label">Total Revenue</div>
                    <div class="value">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                </div>
                <div class="stat-card">
                    <div class="label">Total Users</div>
                    <div class="value">{{ $stats['total_users'] ?? 0 }}</div>
                </div>
                <div class="stat-card">
                    <div class="label">Avg Rating</div>
                    <div class="value">{{ $stats['average_rating'] ?? 0 }}/5</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Today's Activity -->
    <div class="section">
        <div class="section-title">Today's Activity</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-card">
                    <div class="label">New Bookings</div>
                    <div class="value">{{ $stats['new_bookings'] ?? 0 }}</div>
                </div>
                <div class="stat-card">
                    <div class="label">New Users</div>
                    <div class="value">{{ $stats['new_users'] ?? 0 }}</div>
                </div>
                <div class="stat-card">
                    <div class="label">Revenue Today</div>
                    <div class="value">₱{{ number_format($stats['new_revenue'] ?? 0, 2) }}</div>
                </div>
                <div class="stat-card">
                    <div class="label">New Ratings</div>
                    <div class="value">{{ $stats['new_ratings'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    @if($activeTab === 'bookings' || $activeTab === 'all')
        <div class="section">
            <div class="section-title">Booking Analytics</div>
            
            @if(isset($chartImages['bookingTrendsChart']))
                <div class="chart-container">
                    <div class="chart-title">Monthly Booking Trends</div>
                    <img src="{{ $chartImages['bookingTrendsChart'] }}" alt="Booking Trends Chart">
                </div>
            @endif
            
            <div class="stats-grid" style="margin-top: 15px;">
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="label">Pending</div>
                        <div class="value">{{ $stats['pending_bookings'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Acknowledged</div>
                        <div class="value">{{ $stats['acknowledged_bookings'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Approved</div>
                        <div class="value">{{ $stats['approved_bookings'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Completed</div>
                        <div class="value">{{ $stats['completed_bookings'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($activeTab === 'finance' || $activeTab === 'all')
        <div class="section-page-header">Santa Marta Parish - San Roque</div>
        <div class="section">
            <div class="section-title">Financial Analytics</div>
            
            <div class="two-column">
                <div class="column">
                    @if(isset($chartImages['revenueTrendsChart']))
                        <div class="chart-container">
                            <div class="chart-title">Monthly Revenue Trends</div>
                            <img src="{{ $chartImages['revenueTrendsChart'] }}" alt="Revenue Trends Chart">
                        </div>
                    @endif
                </div>
                <div class="column">
                    @if(isset($chartImages['paymentMethodsChart']))
                        <div class="chart-container">
                            <div class="chart-title">Payment Method Distribution</div>
                            <img src="{{ $chartImages['paymentMethodsChart'] }}" alt="Payment Methods Chart">
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="stats-grid" style="margin-top: 15px;">
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="label">Monthly Revenue</div>
                        <div class="value">₱{{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Pending Payments</div>
                        <div class="value">₱{{ number_format($stats['pending_payments'] ?? 0, 2) }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">GCash Payments</div>
                        <div class="value">{{ $stats['gcash_payments'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Metrobank Payments</div>
                        <div class="value">{{ $stats['metrobank_payments'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($activeTab === 'trends' || $activeTab === 'all')
        <div class="section-page-header">Santa Marta Parish - San Roque</div>
        <div class="section">
            <div class="section-title">User Trends Analytics</div>
            
            <div class="two-column">
                <div class="column">
                    @if(isset($chartImages['userTrendsChart']))
                        <div class="chart-container">
                            <div class="chart-title">Monthly User Registration Trends</div>
                            <img src="{{ $chartImages['userTrendsChart'] }}" alt="User Trends Chart">
                        </div>
                    @endif
                </div>
                <div class="column">
                    @if(isset($chartImages['roleDistributionChart']))
                        <div class="chart-container">
                            <div class="chart-title">User Role Distribution</div>
                            <img src="{{ $chartImages['roleDistributionChart'] }}" alt="Role Distribution Chart">
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="stats-grid" style="margin-top: 15px;">
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="label">New This Month</div>
                        <div class="value">{{ $stats['new_users_month'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Active Users</div>
                        <div class="value">{{ $stats['active_users'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Verified Users</div>
                        <div class="value">{{ $stats['verified_users'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Total Users</div>
                        <div class="value">{{ $stats['total_users'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($activeTab === 'ratings' || $activeTab === 'all')
        <div class="section-page-header">Santa Marta Parish - San Roque</div>
        <div class="section">
            <div class="section-title">Service Ratings Analytics</div>
            
            @if(isset($chartImages['ratingDistributionChart']))
                <div class="chart-container">
                    <div class="chart-title">Rating Distribution</div>
                    <img src="{{ $chartImages['ratingDistributionChart'] }}" alt="Rating Distribution Chart">
                </div>
            @endif
            
            <div class="stats-grid" style="margin-top: 15px;">
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="label">Total Ratings</div>
                        <div class="value">{{ $stats['total_ratings'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Average Rating</div>
                        <div class="value">{{ $stats['average_rating'] ?? 0 }}/5</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Rated Services</div>
                        <div class="value">{{ $stats['rated_services'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Unrated Services</div>
                        <div class="value">{{ $stats['unrated_services'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</body>
</html>
