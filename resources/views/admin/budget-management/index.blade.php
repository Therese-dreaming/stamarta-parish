@extends('layouts.admin')

@section('title', 'Budget Management')

@section('content')
@include('components.toast')
<div class="space-y-6">

    <!-- Enhanced Header -->
    <div class="bg-[#0d5c2f] rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-white/5 rounded-tr-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="mr-6 hidden md:block">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center border-2 border-white/30 shadow-lg">
                            <i class="fas fa-chart-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-coins mr-3"></i>
                            Parish Budget Overview
                        </h2>
                        <p class="text-white/90 text-base">Track all financial movements and budget allocations</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Real-time budget tracking and analysis</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end text-white">
                    <div class="text-4xl font-bold mb-1">₱{{ number_format($currentBalance, 2) }}</div>
                    <div class="text-sm opacity-90 mb-4">Current Balance</div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.manual-cash-inflows.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-plus mr-2"></i>
                            Add Cash Inflow
                        </a>
                        <a href="{{ route('admin.manual-cash-inflows.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-list mr-2"></i>
                            View All
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Inflows -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Cash Inflows</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalInflows, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-arrow-down text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="space-y-2">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        <span>Ministry Fund: ₱{{ number_format($totalInflows - $bookingPayments, 2) }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="fas fa-calendar-check text-blue-500 mr-1"></i>
                        <span>Booking Payments: ₱{{ number_format($bookingPayments, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Outflows -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Cash Outflows</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalOutflows, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-arrow-up text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                    <span>Approved Budget Requests</span>
                </div>
            </div>
        </div>

        <!-- Current Balance -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Current Balance</p>
                    <p class="text-2xl font-bold {{ $currentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">₱{{ number_format($currentBalance, 2) }}</p>
                </div>
                <div class="w-12 h-12 {{ $currentBalance >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-wallet text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas {{ $currentBalance >= 0 ? 'fa-check-circle text-green-500' : 'fa-exclamation-triangle text-red-500' }} mr-1"></i>
                    <span>{{ $currentBalance >= 0 ? 'Positive Balance' : 'Negative Balance' }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Recent Approvals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $recentActivity['recent_approvals'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-info-circle text-purple-500 mr-1"></i>
                    <span>Last 30 days</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Timeline -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-history mr-2 text-[#0d5c2f]"></i>
                Budget Balance Timeline
            </h3>
            <p class="text-sm text-gray-600 mt-1">Track how each transaction affects the overall budget balance</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ministry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance Before</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance After</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($budgetTimeline as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item['date']->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $item['date']->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item['type'] === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @if($item['type'] === 'credit')
                                        <i class="fas fa-arrow-down w-3 h-3 mr-1"></i>
                                    @else
                                        <i class="fas fa-arrow-up w-3 h-3 mr-1"></i>
                                    @endif
                                    {{ ucfirst($item['type']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold {{ $item['type'] === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $item['type'] === 'credit' ? '+' : '-' }}₱{{ number_format($item['amount'], 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $item['description'] }}">
                                    {{ $item['description'] }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    @if($item['is_booking_payment'])
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            Booking Payment
                                        </span>
                                    @elseif($item['source_type'] === 'Manual Cash Inflow')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-money-bill-wave mr-1"></i>
                                            Manual Cash Inflow
                                        </span>
                                    @elseif($item['source_type'] === 'Budget Request')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-file-invoice-dollar mr-1"></i>
                                            Budget Request
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-receipt mr-1"></i>
                                            {{ $item['source_type'] }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $item['ministry'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium {{ $item['balance_before'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ₱{{ number_format($item['balance_before'], 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium {{ $item['balance_after'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ₱{{ number_format($item['balance_after'], 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item['is_booking_payment'])
                                    <a href="{{ route('admin.bookings.show', $item['transaction']->booking_id ?? $item['transaction']->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Booking
                                    </a>
                                @elseif($item['source_type'] === 'Manual Cash Inflow')
                                    <a href="{{ route('admin.manual-cash-inflows.show', ['manual_cash_inflow' => $item['transaction']->source_id]) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Cash Inflow
                                    </a>
                                @elseif($item['source_type'] === 'Budget Request')
                                    <a href="{{ route('admin.ministries.ministry-activities.show', $item['transaction']->source_id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-purple-100 text-purple-800 hover:bg-purple-200 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Budget Request
                                    </a>
                                @else
                                    <a href="{{ route('admin.budget-management.show', $item['transaction']->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Transaction
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-chart-line text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">No transactions yet</h3>
                                    <p class="text-sm text-gray-500">Start creating transactions to see the budget timeline.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ministry Summary -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-building mr-2 text-[#0d5c2f]"></i>
                Ministry Budget Summary
            </h3>
            <p class="text-sm text-gray-600 mt-1">Individual ministry financial overview</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ministry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cash Inflows</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cash Outflows</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Requests</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Requests</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ministrySummary as $summary)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-[#0d5c2f] rounded-full flex items-center justify-center">
                                        <i class="fas fa-church text-white text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $summary['ministry']->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-green-600">₱{{ number_format($summary['inflows'], 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-red-600">₱{{ number_format($summary['outflows'], 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold {{ $summary['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ₱{{ number_format($summary['balance'], 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $summary['approved_requests'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $summary['pending_requests'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-building text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">No ministries found</h3>
                                    <p class="text-sm text-gray-500">Create ministries to see budget summaries.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 