@extends('layouts.ministry')

@section('title', 'Cash Inflow Details')

@section('content')
@include('components.toast')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Cash Inflow Details</h1>
                        <p class="text-gray-600 mt-1">{{ $ministry->name }} - Request #{{ $cashInflow->id }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if($cashInflow->status === 'pending')
                        <a href="{{ route('ministry.manual-cash-inflows.edit', $cashInflow) }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Request
                        </a>
                    @endif
                    <a href="{{ route('ministry.manual-cash-inflows.index') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Status Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-lg">
                                    <i class="fas fa-coins text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Amount</p>
                                <p class="text-2xl font-bold text-[#0d5c2f]">₱{{ number_format($cashInflow->amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                                    <i class="fas fa-calendar text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Date Received</p>
                                <p class="text-lg font-bold text-blue-600">{{ $cashInflow->date_received->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-{{ $cashInflow->status === 'pending' ? 'yellow' : ($cashInflow->status === 'approved' ? 'green' : 'red') }}-500 to-{{ $cashInflow->status === 'pending' ? 'yellow' : ($cashInflow->status === 'approved' ? 'green' : 'red') }}-600 flex items-center justify-center shadow-lg">
                                    @if($cashInflow->status === 'pending')
                                        <i class="fas fa-clock text-white text-xl"></i>
                                    @elseif($cashInflow->status === 'approved')
                                        <i class="fas fa-check text-white text-xl"></i>
                                    @else
                                        <i class="fas fa-times text-white text-xl"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                @if($cashInflow->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300">
                                        <i class="fas fa-clock mr-1.5"></i>Pending
                                    </span>
                                @elseif($cashInflow->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300">
                                        <i class="fas fa-check mr-1.5"></i>Approved
                                    </span>
                                @elseif($cashInflow->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300">
                                        <i class="fas fa-times mr-1.5"></i>Rejected
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Details -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] px-8 py-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>
                            Request Details
                        </h2>
                        <p class="text-white/80 text-sm mt-1">Complete information about this cash inflow request</p>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Description</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl">{{ $cashInflow->description }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Source Type</label>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                        @if($cashInflow->source_type === 'diocese')
                                            <i class="fas fa-building text-blue-600 text-xs"></i>
                                        @elseif($cashInflow->source_type === 'donation')
                                            <i class="fas fa-gift text-blue-600 text-xs"></i>
                                        @elseif($cashInflow->source_type === 'fundraising')
                                            <i class="fas fa-ticket-alt text-blue-600 text-xs"></i>
                                        @elseif($cashInflow->source_type === 'event_revenue')
                                            <i class="fas fa-calendar-check text-blue-600 text-xs"></i>
                                        @elseif($cashInflow->source_type === 'membership_fee')
                                            <i class="fas fa-users text-blue-600 text-xs"></i>
                                        @elseif($cashInflow->source_type === 'sponsorship')
                                            <i class="fas fa-handshake text-blue-600 text-xs"></i>
                                        @else
                                            <i class="fas fa-file-alt text-blue-600 text-xs"></i>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl flex-1">{{ ucfirst(str_replace('_', ' ', $cashInflow->source_type ?? 'N/A')) }}</span>
                                </div>
                            </div>
                            @if($cashInflow->source_details)
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Source Details</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl">{{ $cashInflow->source_details }}</p>
                            </div>
                            @endif
                            @if($cashInflow->other_source_specify)
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Other Source Specify</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl">{{ $cashInflow->other_source_specify }}</p>
                            </div>
                            @endif
                            @if($cashInflow->reference_no)
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Reference Number</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl font-mono">{{ $cashInflow->reference_no }}</p>
                            </div>
                            @endif
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Requested By</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl">{{ $cashInflow->requestedBy ? $cashInflow->requestedBy->name : 'Unknown' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Request Date</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl">{{ $cashInflow->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($cashInflow->notes)
                        <div class="mt-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes</label>
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $cashInflow->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($cashInflow->status !== 'pending')
                <!-- Approval/Rejection Details -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-{{ $cashInflow->status === 'approved' ? 'green' : 'red' }}-500 to-{{ $cashInflow->status === 'approved' ? 'green' : 'red' }}-600 px-8 py-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-{{ $cashInflow->status === 'approved' ? 'check-circle' : 'times-circle' }} mr-3"></i>
                            {{ $cashInflow->status === 'approved' ? 'Approval' : 'Rejection' }} Details
                        </h2>
                        <p class="text-white/80 text-sm mt-1">{{ $cashInflow->status === 'approved' ? 'Information about the approval process' : 'Information about the rejection' }}</p>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    {{ $cashInflow->status === 'approved' ? 'Approved By' : 'Rejected By' }}
                                </label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl">
                                    {{ $cashInflow->status === 'approved' ? ($cashInflow->approvedBy ? $cashInflow->approvedBy->name : 'Unknown') : ($cashInflow->rejectedBy ? $cashInflow->rejectedBy->name : 'Unknown') }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    {{ $cashInflow->status === 'approved' ? 'Approval' : 'Rejection' }} Date
                                </label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl">
                                    {{ $cashInflow->status === 'approved' ? ($cashInflow->approved_at ? $cashInflow->approved_at->format('M d, Y g:i A') : 'N/A') : ($cashInflow->rejected_at ? $cashInflow->rejected_at->format('M d, Y g:i A') : 'N/A') }}
                                </p>
                            </div>
                            @if($cashInflow->status === 'rejected' && $cashInflow->rejection_reason)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Rejection Reason</label>
                                <div class="bg-red-50 border border-red-200 p-4 rounded-xl">
                                    <p class="text-sm text-red-800">{{ $cashInflow->rejection_reason }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Actions Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-cogs mr-2"></i>
                            Actions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @if($cashInflow->status === 'pending')
                                <a href="{{ route('ministry.manual-cash-inflows.edit', $cashInflow) }}" 
                                   class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Request
                                </a>
                                <form action="{{ route('ministry.manual-cash-inflows.destroy', $cashInflow) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this cash inflow request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete Request
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('ministry.manual-cash-inflows.index') }}" 
                               class="w-full inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 border border-gray-300 hover:border-gray-400">
                                <i class="fas fa-list mr-2"></i>
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Request Timeline -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-history mr-2"></i>
                            Request Timeline
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <div class="relative px-1">
                                                    <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center ring-8 ring-white shadow-lg">
                                                        <i class="fas fa-paper-plane text-white text-sm"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Request submitted</p>
                                                    <p class="text-xs text-gray-500">Cash inflow request created</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $cashInflow->created_at->format('Y-m-d') }}">
                                                        {{ $cashInflow->created_at->format('M d, Y') }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @if($cashInflow->status === 'approved')
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <div class="relative px-1">
                                                    <div class="h-10 w-10 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center ring-8 ring-white shadow-lg">
                                                        <i class="fas fa-check text-white text-sm"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Request approved</p>
                                                    <p class="text-xs text-gray-500">Funds added to ministry budget</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $cashInflow->approved_at ? $cashInflow->approved_at->format('Y-m-d') : '' }}">
                                                        {{ $cashInflow->approved_at ? $cashInflow->approved_at->format('M d, Y') : 'N/A' }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @elseif($cashInflow->status === 'rejected')
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <div class="relative px-1">
                                                    <div class="h-10 w-10 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center ring-8 ring-white shadow-lg">
                                                        <i class="fas fa-times text-white text-sm"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Request rejected</p>
                                                    <p class="text-xs text-gray-500">Request was not approved</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $cashInflow->rejected_at ? $cashInflow->rejected_at->format('Y-m-d') : '' }}">
                                                        {{ $cashInflow->rejected_at ? $cashInflow->rejected_at->format('M d, Y') : 'N/A' }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Ministry Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-church mr-2"></i>
                            Ministry Info
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-building text-white text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-green-800">Ministry</span>
                                </div>
                                <span class="text-sm font-bold text-green-600">{{ $ministry->name }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-blue-800">Head</span>
                                </div>
                                <span class="text-sm font-bold text-blue-600">{{ $ministry->head->name ?? 'Not Assigned' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Quick Stats
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar text-white text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">Days Since Request</span>
                                </div>
                                <span class="text-lg font-bold text-gray-600">{{ $cashInflow->created_at->diffInDays(now()) }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clock text-white text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">Last Updated</span>
                                </div>
                                <span class="text-sm font-bold text-gray-600">{{ $cashInflow->updated_at->format('M d') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for enhanced styling -->
<style>
    /* Smooth transitions */
    * {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Enhanced hover effects */
    .hover-lift:hover {
        transform: translateY(-2px);
    }
    
    /* Timeline styling */
    .flow-root ul li:last-child .relative {
        padding-bottom: 0;
    }
</style>

@endsection