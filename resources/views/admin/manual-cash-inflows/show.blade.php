@extends('layouts.admin')

@section('title', 'Cash Inflow Details')

@section('content')
@include('components.toast')

<div class="space-y-6">
    <!-- Enhanced Header with Gradient Background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#1a8045] rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-bl-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center">
                    <div class="mr-5 hidden md:block">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/30 shadow-lg">
                            <i class="fas fa-receipt text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <a href="{{ route('admin.manual-cash-inflows.index') }}" 
                               class="inline-flex items-center px-3 py-2 border border-white/30 text-sm font-medium rounded-lg text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Cash Inflows
                            </a>
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-500', 'icon' => 'fa-clock'],
                                    'approved' => ['bg' => 'bg-green-500', 'icon' => 'fa-check'],
                                    'rejected' => ['bg' => 'bg-red-500', 'icon' => 'fa-times']
                                ];
                                $config = $statusConfig[$manual_cash_inflow->status];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white">
                                <i class="fas {{ $config['icon'] }} mr-2"></i>
                                {{ ucfirst($manual_cash_inflow->status) }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-white">Manual Cash Inflow #{{ $manual_cash_inflow->id }}</h1>
                        <p class="text-white/80 mt-2">{{ $manual_cash_inflow->description }}</p>
                    </div>
                </div>
                <div class="text-right text-white mt-4 md:mt-0">
                    <div class="text-3xl font-bold">₱{{ number_format($manual_cash_inflow->amount, 2) }}</div>
                    <div class="text-sm opacity-80">Cash Inflow Amount</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Cash Inflow Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-receipt mr-2 text-[#0d5c2f]"></i>
                        Cash Inflow Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-bullseye text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Description</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $manual_cash_inflow->description }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-peso-sign text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Amount</label>
                                    <p class="text-lg font-semibold text-[#0d5c2f]">₱{{ number_format($manual_cash_inflow->amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-tag text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Source Type</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $manual_cash_inflow->getSourceTypeLabel() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-church text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Ministry</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $manual_cash_inflow->ministry ? $manual_cash_inflow->ministry->name : 'General Parish Fund' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($manual_cash_inflow->source_details)
                        <div class="md:col-span-2 bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                                    <i class="fas fa-info-circle text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500">Source Details</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ $manual_cash_inflow->source_details }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($manual_cash_inflow->source_type === 'other' && $manual_cash_inflow->other_source_specify)
                        <div class="md:col-span-2 bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                                    <i class="fas fa-ellipsis-h text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500">Other Source Type</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ $manual_cash_inflow->other_source_specify }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($manual_cash_inflow->notes)
                        <div class="md:col-span-2 bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                                    <i class="fas fa-sticky-note text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500">Notes</label>
                                    <p class="text-sm text-gray-900 mt-1 whitespace-pre-line">{{ $manual_cash_inflow->notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ministry Budget Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-[#0d5c2f]"></i>
                        Ministry Budget Statistics
                    </h3>
                </div>
                <div class="p-6">
                    @if($manual_cash_inflow->ministry)
                        <div class="space-y-6">
                            <!-- Budget Overview -->
                            <div class="bg-gradient-to-r from-[#0d5c2f]/10 to-[#1a8045]/10 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $manual_cash_inflow->ministry->name }}</h4>
                                        <p class="text-sm text-gray-600">Budget Overview</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-[#0d5c2f]">₱{{ number_format($ministryStats['total_budget'], 2) }}</div>
                                        <div class="text-sm text-gray-500">Total Allocated</div>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">Budget Utilization</span>
                                        <span class="text-sm font-bold text-[#0d5c2f]">{{ number_format($ministryStats['utilization_percentage'], 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-[#0d5c2f] to-[#1a8045] h-3 rounded-full transition-all duration-500" 
                                             style="width: {{ $ministryStats['utilization_percentage'] }}%"></div>
                                    </div>
                                </div>
                                
                                <!-- Budget Breakdown -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-white/50 rounded-lg p-4 text-center">
                                        <div class="text-lg font-bold text-green-600">₱{{ number_format($ministryStats['used_budget'], 2) }}</div>
                                        <div class="text-xs text-gray-600">Used</div>
                                    </div>
                                    <div class="bg-white/50 rounded-lg p-4 text-center">
                                        <div class="text-lg font-bold text-blue-600">₱{{ number_format($ministryStats['remaining_budget'], 2) }}</div>
                                        <div class="text-xs text-gray-600">Remaining</div>
                                    </div>
                                    <div class="bg-white/50 rounded-lg p-4 text-center">
                                        <div class="text-lg font-bold text-purple-600">{{ $ministryStats['approved_requests'] }}</div>
                                        <div class="text-xs text-gray-600">Approved Requests</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Recent Activity -->
                            <div class="space-y-3">
                                <h5 class="text-sm font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-history mr-2 text-[#0d5c2f]"></i>
                                    Recent Budget Activity
                                </h5>
                                @if($ministryStats['recent_transactions']->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($ministryStats['recent_transactions'] as $transaction)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full {{ $transaction->type === 'inflow' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center mr-3">
                                                    <i class="fas {{ $transaction->type === 'inflow' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }} text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $transaction->description }}</p>
                                                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-semibold {{ $transaction->type === 'inflow' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $transaction->type === 'inflow' ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4 text-gray-500">
                                        <i class="fas fa-chart-line text-2xl mb-2"></i>
                                        <p class="text-sm">No recent budget activity</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-church text-gray-400 text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">General Parish Fund</h4>
                            <p class="text-sm text-gray-600 mb-4">This cash inflow is allocated to the general parish fund</p>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-gray-700">₱{{ number_format($generalFundStats['total_inflows'], 2) }}</div>
                                <div class="text-sm text-gray-500">Total General Fund Inflows</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Approval Actions -->
            @if($manual_cash_inflow->isPending())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-gavel mr-2 text-[#0d5c2f]"></i>
                        Approval Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <label class="block text-sm font-medium text-green-800 mb-2">Approve Cash Inflow</label>
                            <p class="text-sm text-green-700 mb-4">This will add ₱{{ number_format($manual_cash_inflow->amount, 2) }} to the budget.</p>
                            <button type="button" onclick="openApproveModal()" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-check mr-2"></i>
                                Approve & Add to Budget
                            </button>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <label class="block text-sm font-medium text-red-800 mb-2">Reject Cash Inflow</label>
                            <p class="text-sm text-red-700 mb-4">Provide a reason for rejection.</p>
                            <button type="button" onclick="openRejectModal()" class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-times mr-2"></i>
                                Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sticky Sidebar -->
        <div class="space-y-6 lg:sticky lg:top-4 self-start">
            <!-- Request Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>
                        Request Timeline
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Submitted Step -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-file-alt text-white text-sm"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-blue-900">Cash Inflow Submitted</h4>
                                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Completed</span>
                                    </div>
                                    <p class="text-xs text-blue-700 mb-2">{{ $manual_cash_inflow->created_at->format('M d, Y \a\t g:i A') }}</p>
                                    <p class="text-xs text-blue-600">
                                        Manual cash inflow has been submitted for review
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Review Step -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-search text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-yellow-900">Under Review</h4>
                                        <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">
                                            @if($manual_cash_inflow->status === 'pending')
                                                Active
                                            @else
                                                Completed
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-yellow-700 mb-2">
                                        @if($manual_cash_inflow->status === 'pending')
                                            Currently being reviewed
                                        @else
                                            {{ $manual_cash_inflow->approved_at ? $manual_cash_inflow->approved_at->format('M d, Y \a\t g:i A') : 'Review completed' }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-yellow-600">
                                        @if($manual_cash_inflow->status === 'pending')
                                            Awaiting administrative approval
                                        @else
                                            Review process completed
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Decision Step -->
                        @if($manual_cash_inflow->status !== 'pending')
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-r {{ $manual_cash_inflow->status === 'approved' ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600' }} rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas {{ $manual_cash_inflow->status === 'approved' ? 'fa-check' : 'fa-times' }} text-white text-sm"></i>
                                    </div>
                                    @if($manual_cash_inflow->status === 'approved')
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-{{ $manual_cash_inflow->status === 'approved' ? 'green' : 'red' }}-50 rounded-lg p-4 border border-{{ $manual_cash_inflow->status === 'approved' ? 'green' : 'red' }}-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-{{ $manual_cash_inflow->status === 'approved' ? 'green' : 'red' }}-900">
                                            Cash Inflow {{ ucfirst($manual_cash_inflow->status) }}
                                        </h4>
                                        <span class="text-xs text-{{ $manual_cash_inflow->status === 'approved' ? 'green' : 'red' }}-600 bg-{{ $manual_cash_inflow->status === 'approved' ? 'green' : 'red' }}-100 px-2 py-1 rounded-full">Completed</span>
                                    </div>
                                    <p class="text-xs text-{{ $manual_cash_inflow->status === 'approved' ? 'green' : 'red' }}-700 mb-2">
                                        {{ $manual_cash_inflow->approved_at->format('M d, Y \a\t g:i A') }}
                                        @if($manual_cash_inflow->approvedBy)
                                            by {{ $manual_cash_inflow->approvedBy->name }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-{{ $manual_cash_inflow->status === 'approved' ? 'green' : 'red' }}-600">
                                        @if($manual_cash_inflow->status === 'approved')
                                            Cash inflow has been approved and added to budget
                                        @else
                                            Cash inflow has been rejected
                                            @if($manual_cash_inflow->rejection_reason)
                                                <br><span class="font-medium">Reason: {{ $manual_cash_inflow->rejection_reason }}</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Pending Decision Step -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-clock text-gray-500 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-gray-700">Decision Pending</h4>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Waiting</span>
                                    </div>
                                    <p class="text-xs text-gray-600">Awaiting administrative decision</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                        Request Details
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-hashtag mr-2 text-[#0d5c2f]"></i>Reference No.
                            </span>
                            <span class="text-xs font-medium text-gray-900 font-mono">{{ $manual_cash_inflow->reference_no ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>Entered By
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $manual_cash_inflow->enteredBy->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-calendar mr-2 text-[#0d5c2f]"></i>Created Date
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $manual_cash_inflow->created_at->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                        @if($manual_cash_inflow->approvedBy)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-user-check mr-2 text-[#0d5c2f]"></i>{{ $manual_cash_inflow->status === 'approved' ? 'Approved' : 'Rejected' }} By
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $manual_cash_inflow->approvedBy->name }}</span>
                        </div>
                        @endif
                        @if($manual_cash_inflow->rejection_reason)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-2 mt-0.5"></i>
                                <div>
                                    <span class="text-xs font-medium text-red-800">Rejection Reason</span>
                                    <p class="text-xs text-red-700 mt-1">{{ $manual_cash_inflow->rejection_reason }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
    <div class="relative top-20 mx-auto p-6 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="approveModalContent">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Confirm Approval</h3>
                    </div>
                    <button onclick="closeApproveModal()" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.manual-cash-inflows.approve', ['manual_cash_inflow' => $manual_cash_inflow->id]) }}" method="POST">
                @csrf
                <div class="px-6 py-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-peso-sign text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-gray-700 text-lg">Approve this cash inflow and add</p>
                        <p class="text-3xl font-bold text-green-600 my-2">₱{{ number_format($manual_cash_inflow->amount, 2) }}</p>
                        <p class="text-gray-700">to the budget?</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-green-600 mt-0.5"></i>
                            <div class="text-sm text-green-800">
                                <p class="font-semibold mb-1">This action will:</p>
                                <ul class="space-y-1 text-green-700">
                                    <li>• Add the amount to the ministry budget</li>
                                    <li>• Mark the cash inflow as approved</li>
                                    <li>• Record the approval timestamp</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeApproveModal()" class="px-6 py-3 text-sm font-semibold rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-3 text-sm font-semibold rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-check mr-2"></i>
                        Approve Cash Inflow
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
    <div class="relative top-20 mx-auto p-6 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="rejectModalContent">
            <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-times text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Reject Cash Inflow</h3>
                    </div>
                    <button onclick="closeRejectModal()" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.manual-cash-inflows.reject', ['manual_cash_inflow' => $manual_cash_inflow->id]) }}" method="POST">
                @csrf
                <div class="px-6 py-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times text-red-600 text-2xl"></i>
                        </div>
                        <p class="text-gray-700 text-lg">Provide a reason for rejection</p>
                        <p class="text-sm text-gray-500">This will be saved with the record for future reference</p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="rejection_reason" class="block text-sm font-semibold text-gray-700 mb-2">Rejection Reason</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="4" class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm p-4 resize-none" placeholder="Please provide a detailed reason for rejecting this cash inflow..." required></textarea>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-triangle text-red-600 mt-0.5"></i>
                                <div class="text-sm text-red-800">
                                    <p class="font-semibold mb-1">This action will:</p>
                                    <ul class="space-y-1 text-red-700">
                                        <li>• Mark the cash inflow as rejected</li>
                                        <li>• Save the rejection reason</li>
                                        <li>• Record the rejection timestamp</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-6 py-3 text-sm font-semibold rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-3 text-sm font-semibold rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-times mr-2"></i>
                        Reject Cash Inflow
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-24 mx-auto p-5 w-full max-w-2xl">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Full Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <div class="px-5 py-4 space-y-4 text-sm">
                <div>
                    <div class="text-gray-500 mb-1">Description</div>
                    <div class="bg-gray-50 rounded-lg p-3 text-gray-900">{{ $manual_cash_inflow->description }}</div>
                </div>
                @if($manual_cash_inflow->source_details)
                <div>
                    <div class="text-gray-500 mb-1">Source Details</div>
                    <div class="bg-gray-50 rounded-lg p-3 text-gray-900">{{ $manual_cash_inflow->source_details }}</div>
                </div>
                @endif
                @if($manual_cash_inflow->notes)
                <div>
                    <div class="text-gray-500 mb-1">Notes</div>
                    <div class="bg-gray-50 rounded-lg p-3 text-gray-900 whitespace-pre-line">{{ $manual_cash_inflow->notes }}</div>
                </div>
                @endif
            </div>
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-end">
                <button type="button" onclick="closeDetailsModal()" class="px-4 py-2 text-xs rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Close</button>
            </div>
        </div>
    </div>
    </div>

<script>
// Enhanced modal functions with animations
function openRejectModal() { 
    const modal = document.getElementById('rejectModal');
    const content = document.getElementById('rejectModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.style.transform = 'scale(1)';
        content.style.opacity = '1';
    }, 10);
}

function closeRejectModal() { 
    const modal = document.getElementById('rejectModal');
    const content = document.getElementById('rejectModalContent');
    content.style.transform = 'scale(0.95)';
    content.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function openApproveModal() { 
    const modal = document.getElementById('approveModal');
    const content = document.getElementById('approveModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.style.transform = 'scale(1)';
        content.style.opacity = '1';
    }, 10);
}

function closeApproveModal() { 
    const modal = document.getElementById('approveModal');
    const content = document.getElementById('approveModalContent');
    content.style.transform = 'scale(0.95)';
    content.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function openDetailsModal() { 
    document.getElementById('detailsModal').classList.remove('hidden'); 
}

function closeDetailsModal() { 
    document.getElementById('detailsModal').classList.add('hidden'); 
}

// Enhanced modal close functionality
['rejectModal','approveModal','detailsModal'].forEach(function(id){
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('click', function(e){ 
            if (e.target === this) { 
                if (id === 'rejectModal') {
                    closeRejectModal();
                } else if (id === 'approveModal') {
                    closeApproveModal();
                } else {
                    closeDetailsModal();
                }
            } 
        });
    }
});

// Add smooth scroll behavior
document.documentElement.style.scrollBehavior = 'smooth';

// Add loading states for form submissions
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            }
        });
    });
});

// Add hover effects for cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.group');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection 