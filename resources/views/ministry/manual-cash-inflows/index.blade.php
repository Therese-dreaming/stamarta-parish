@extends('layouts.ministry')

@section('title', 'Manual Cash Inflows')

@section('content')
@include('components.toast')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Manual Cash Inflows</h1>
                        <p class="text-gray-600 mt-1">{{ $ministry->name }} - Cash Inflow Management</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('ministry.manual-cash-inflows.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] hover:from-[#0a4a26] hover:to-[#0d5c2f] text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i>
                        New Cash Inflow
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Amount -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-coins text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Amount</p>
                        <p class="text-2xl font-bold text-blue-600">₱{{ number_format($totalAmount, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending Amount -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Amount</p>
                        <p class="text-2xl font-bold text-yellow-600">₱{{ number_format($pendingAmount, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Approved Amount -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-check text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Approved Amount</p>
                        <p class="text-2xl font-bold text-green-600">₱{{ number_format($approvedAmount, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Rejected Amount -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-times text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Rejected Amount</p>
                        <p class="text-2xl font-bold text-red-600">₱{{ number_format($rejectedAmount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] px-8 py-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-filter mr-3"></i>
                    Filter & Search
                </h2>
                <p class="text-white/80 text-sm mt-1">Filter cash inflow requests by status, date range, or search terms</p>
            </div>
            <div class="p-8">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Status Filter -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Status</label>
                        <div class="relative">
                            <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200 appearance-none bg-white">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>�� Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>�� Rejected</option>
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Start Date -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200">
                    </div>

                    <!-- End Date -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200">
                    </div>

                    <!-- Search -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search description..." 
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0d5c2f]/20 focus:border-[#0d5c2f] transition-all duration-200">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="md:col-span-4 flex justify-between items-center pt-4">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] hover:from-[#0a4a26] hover:to-[#0d5c2f] text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-search mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('ministry.manual-cash-inflows.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 border border-gray-300 hover:border-gray-400">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Counts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-r from-yellow-500 to-yellow-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-yellow-600 mb-2">{{ $pendingCount }}</p>
                <p class="text-sm font-medium text-gray-500">Pending Requests</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-check text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-green-600 mb-2">{{ $approvedCount }}</p>
                <p class="text-sm font-medium text-gray-500">Approved Requests</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-times text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-red-600 mb-2">{{ $rejectedCount }}</p>
                <p class="text-sm font-medium text-gray-500">Rejected Requests</p>
            </div>
        </div>

        <!-- Cash Inflows Table -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] px-8 py-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-list mr-3"></i>
                    Cash Inflow Requests
                </h2>
                <p class="text-white/80 text-sm mt-1">Manage and track your cash inflow requests</p>
            </div>
            
            <div class="overflow-hidden">
                @if($cashInflows->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cashInflows as $cashInflow)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center mr-3">
                                                    <i class="fas fa-calendar text-gray-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium">{{ $cashInflow->created_at->format('M d, Y') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $cashInflow->created_at->format('h:i A') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-sm font-bold text-[#0d5c2f] text-lg">
                                            ₱{{ number_format($cashInflow->amount, 2) }}
                                        </td>
                                        <td class="px-8 py-6 text-sm text-gray-900">
                                            <div class="max-w-xs">
                                                <div class="font-medium">{{ Str::limit($cashInflow->description, 50) }}</div>
                                                @if($cashInflow->source_details)
                                                    <div class="text-xs text-gray-500 mt-1">{{ Str::limit($cashInflow->source_details, 30) }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-900">
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
                                                <div>
                                                    <div class="font-medium">{{ ucfirst(str_replace('_', ' ', $cashInflow->source_type ?? 'N/A')) }}</div>
                                                    @if($cashInflow->other_source_specify)
                                                        <div class="text-xs text-gray-500">{{ $cashInflow->other_source_specify }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            @if($cashInflow->status === 'pending')
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300">
                                                    <i class="fas fa-clock mr-1.5"></i>Pending
                                                </span>
                                            @elseif($cashInflow->status === 'approved')
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300">
                                                    <i class="fas fa-check mr-1.5"></i>Approved
                                                </span>
                                            @elseif($cashInflow->status === 'rejected')
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300">
                                                    <i class="fas fa-times mr-1.5"></i>Rejected
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('ministry.manual-cash-inflows.show', $cashInflow) }}" 
                                                   class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-600 hover:text-blue-700 transition-all duration-200 transform hover:scale-105"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($cashInflow->status === 'pending')
                                                    <a href="{{ route('ministry.manual-cash-inflows.edit', $cashInflow) }}" 
                                                       class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-yellow-100 hover:bg-yellow-200 text-yellow-600 hover:text-yellow-700 transition-all duration-200 transform hover:scale-105"
                                                       title="Edit Request">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('ministry.manual-cash-inflows.destroy', $cashInflow) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this cash inflow request?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 transition-all duration-200 transform hover:scale-105"
                                                                title="Delete Request">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-8 py-6 border-t border-gray-200 bg-gray-50">
                        {{ $cashInflows->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <i class="fas fa-coins text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Cash Inflow Requests</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">You haven't created any cash inflow requests yet. Start by creating your first request to track incoming funds for your ministry.</p>
                        <a href="{{ route('ministry.manual-cash-inflows.create') }}" 
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#0d5c2f] to-[#0a4a26] hover:from-[#0a4a26] hover:to-[#0d5c2f] text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus mr-2"></i>
                            Create First Request
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for enhanced styling -->
<style>
    /* Custom scrollbar for select dropdowns */
    select::-webkit-scrollbar {
        width: 8px;
    }
    
    select::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    select::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    select::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Enhanced hover effects */
    .hover-lift:hover {
        transform: translateY(-2px);
    }
    
    /* Smooth transitions */
    * {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Table row hover effect */
    tbody tr:hover {
        background-color: #f8fafc;
    }
</style>

@endsection