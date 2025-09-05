@extends('layouts.ministry')

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
                            {{ $ministry->name }} Budget Overview
                        </h2>
                        <p class="text-white/90 text-base">Track ministry financial movements and budget allocations</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Real-time budget tracking and analysis</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end text-white">
                    <div class="text-4xl font-bold mb-1">₱{{ number_format($remainingBudget, 2) }}</div>
                    <div class="text-sm opacity-90 mb-4">Remaining Budget</div>
                    <div class="flex space-x-3">
                        <a href="{{ route('ministry.budget-management.show') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-chart-line mr-2"></i>
                            Detailed View
                        </a>
                        <a href="{{ route('ministry.activities.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-plus mr-2"></i>
                            New Activity
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Budget -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Budget</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalBudget, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-coins text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-info-circle text-green-500 mr-1"></i>
                    <span>Allocated Funds</span>
                </div>
            </div>
        </div>

        <!-- Remaining Budget -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Remaining Budget</p>
                    <p class="text-2xl font-bold {{ $remainingBudget >= 0 ? 'text-green-600' : 'text-red-600' }}">₱{{ number_format($remainingBudget, 2) }}</p>
                </div>
                <div class="w-12 h-12 {{ $remainingBudget >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-wallet text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas {{ $remainingBudget >= 0 ? 'fa-check-circle text-green-500' : 'fa-exclamation-triangle text-red-500' }} mr-1"></i>
                    <span>{{ $remainingBudget >= 0 ? 'Available Funds' : 'Over Budget' }}</span>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Expenses</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalExpenses, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-arrow-down text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                    <span>Approved Budget Requests</span>
                </div>
            </div>
        </div>

        <!-- Budget Usage -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Budget Usage</p>
                    @php
                        $usagePercentage = $totalBudget > 0 ? min(100, ($totalExpenses / $totalBudget) * 100) : 0;
                    @endphp
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($usagePercentage, 1) }}%</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-percentage text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-info-circle text-purple-500 mr-1"></i>
                    <span>Utilization Rate</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Requests Summary -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-file-invoice-dollar mr-2 text-[#0d5c2f]"></i>
                Budget Requests Summary
            </h3>
            <p class="text-sm text-gray-600 mt-1">Track your ministry's budget request status</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Pending Requests -->
                <div class="text-center p-6 bg-yellow-50 rounded-xl border border-yellow-200 hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-yellow-600 mb-2">{{ $pendingRequests }}</p>
                    <p class="text-sm font-medium text-gray-700">Pending Requests</p>
                    <p class="text-xs text-gray-500 mt-1">Awaiting approval</p>
                </div>

                <!-- Approved Requests -->
                <div class="text-center p-6 bg-green-50 rounded-xl border border-green-200 hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-green-600 mb-2">{{ $approvedRequests }}</p>
                    <p class="text-sm font-medium text-gray-700">Approved Requests</p>
                    <p class="text-xs text-gray-500 mt-1">Funds allocated</p>
                </div>

                <!-- Rejected Requests -->
                <div class="text-center p-6 bg-red-50 rounded-xl border border-red-200 hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-times text-red-600 text-xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-red-600 mb-2">{{ $rejectedRequests }}</p>
                    <p class="text-sm font-medium text-gray-700">Rejected Requests</p>
                    <p class="text-xs text-gray-500 mt-1">Not approved</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-history mr-2 text-[#0d5c2f]"></i>
                Recent Transactions
            </h3>
            <p class="text-sm text-gray-600 mt-1">Latest financial activities for your ministry</p>
        </div>
        
        <div class="overflow-x-auto">
            @if($transactions->count() > 0)
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions->take(10) as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->type === 'credit')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-down w-3 h-3 mr-1"></i>
                                            Credit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-up w-3 h-3 mr-1"></i>
                                            Debit
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $transaction->description }}">
                                        {{ Str::limit($transaction->description, 50) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-500">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-receipt text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">No transactions yet</h3>
                        <p class="text-sm text-gray-500">Start creating budget requests to see transaction history.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-calendar mr-2 text-[#0d5c2f]"></i>
                Recent Activities
            </h3>
            <p class="text-sm text-gray-600 mt-1">Latest ministry activities and their budget status</p>
        </div>
        
        <div class="p-6">
            @if($recentActivities->count() > 0)
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-4 shadow-lg">
                                    <i class="fas fa-calendar text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->start_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($activity->estimated_budget)
                                    <div class="text-right">
                                        <span class="text-sm font-medium text-gray-900">₱{{ number_format($activity->estimated_budget, 2) }}</span>
                                        <p class="text-xs text-gray-500">Estimated Budget</p>
                                    </div>
                                @endif
                                @if($activity->pendingBudgetRequest)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending Budget
                                    </span>
                                @elseif($activity->approvedBudgetRequest)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check mr-1"></i>
                                        Budget Approved
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-500">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">No recent activities</h3>
                        <p class="text-sm text-gray-500">Create new activities to see them listed here.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection