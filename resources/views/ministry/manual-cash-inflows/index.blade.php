@extends('layouts.ministry')

@section('title', 'Manual Cash Inflows - ' . $ministry->name)

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
                            <i class="fas fa-coins text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <a href="{{ route('ministry.dashboard') }}" 
                               class="inline-flex items-center px-3 py-2 border border-white/30 text-sm font-medium rounded-lg text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Dashboard
                            </a>
                        </div>
                        <h1 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-coins mr-3"></i>
                            {{ $ministry->name }} Cash Inflows
                        </h1>
                        <p class="text-white/90 text-base">Manage your ministry cash inflow requests and tracking</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Total: {{ $cashInflows->total() }} requests</span>
                        </div>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold mb-1">₱{{ number_format($totalAmount, 0) }}</div>
                    <div class="text-sm opacity-90">Total Amount</div>
                    <div class="flex space-x-3 mt-4">
                        <a href="{{ route('ministry.manual-cash-inflows.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-plus mr-2"></i>
                            New Cash Inflow
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalAmount, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#0d5c2f] rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-coins text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-[#0d5c2f] mr-1"></i>
                    <span>All requests</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending Amount</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($pendingAmount, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-yellow-500 mr-1"></i>
                    <span>Awaiting approval</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Approved Amount</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($approvedAmount, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span>Approved requests</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Rejected Amount</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($rejectedAmount, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-times text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-red-500 mr-1"></i>
                    <span>Rejected requests</span>
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle (Full width tab style) -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="flex border-b border-gray-200">
            <button id="table-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-table mr-2"></i> Table View
            </button>
            <button id="card-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-th-large mr-2"></i> Cards View
            </button>
        </div>
        
        <div class="p-4">
            @if($cashInflows->count() > 0)
                <!-- Table View -->
                <div id="table-view" class="overflow-x-auto hidden animate-fadeIn">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cashInflows as $cashInflow)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 animate-slideInUp" style="animation-delay: {{ $loop->index * 50 }}ms">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-[#0d5c2f] flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                            <i class="fas fa-calendar text-white text-sm"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $cashInflow->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $cashInflow->created_at->format('h:i A') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-bold text-[#0d5c2f]">₱{{ number_format($cashInflow->amount, 2) }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <div class="max-w-xs">
                                        <div class="font-medium">{{ Str::limit($cashInflow->description, 50) }}</div>
                                        @if($cashInflow->source_details)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($cashInflow->source_details, 30) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
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
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($cashInflow->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @elseif($cashInflow->status === 'approved')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-check mr-1"></i>Approved
                                        </span>
                                    @elseif($cashInflow->status === 'rejected')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-times mr-1"></i>Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('ministry.manual-cash-inflows.show', $cashInflow) }}" class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" title="View">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        @if($cashInflow->status === 'pending')
                                            <a href="{{ route('ministry.manual-cash-inflows.edit', $cashInflow) }}" class="w-7 h-7 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-all duration-200 hover:scale-110" title="Edit">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('ministry.manual-cash-inflows.destroy', $cashInflow) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this cash inflow request?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110" title="Delete">
                                                    <i class="fas fa-trash text-xs"></i>
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

                <!-- Card View -->
                <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 animate-fadeIn">
                    @foreach($cashInflows as $cashInflow)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-slideInUp" style="animation-delay: {{ $loop->index * 100 }}ms">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-xl bg-[#0d5c2f] flex items-center justify-center shadow-lg">
                                    <i class="fas fa-coins text-white text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-lg font-bold text-[#0d5c2f]">₱{{ number_format($cashInflow->amount, 2) }}</div>
                                    <div class="text-xs text-gray-500">{{ $cashInflow->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                            @if($cashInflow->status === 'pending')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif($cashInflow->status === 'approved')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Approved
                                </span>
                            @elseif($cashInflow->status === 'rejected')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Rejected
                                </span>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-900 mb-1">{{ Str::limit($cashInflow->description, 40) }}</h3>
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center mr-2">
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
                                <span>{{ ucfirst(str_replace('_', ' ', $cashInflow->source_type ?? 'N/A')) }}</span>
                            </div>
                            @if($cashInflow->source_details)
                                <p class="text-xs text-gray-500 mt-2">{{ Str::limit($cashInflow->source_details, 60) }}</p>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('ministry.manual-cash-inflows.show', $cashInflow) }}" class="w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" title="View">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @if($cashInflow->status === 'pending')
                                    <a href="{{ route('ministry.manual-cash-inflows.edit', $cashInflow) }}" class="w-8 h-8 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-all duration-200 hover:scale-110" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('ministry.manual-cash-inflows.destroy', $cashInflow) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this cash inflow request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110" title="Delete">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $cashInflow->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
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

<!-- JavaScript for view toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableViewBtn = document.getElementById('table-view-btn');
    const cardViewBtn = document.getElementById('card-view-btn');
    const tableView = document.getElementById('table-view');
    const cardView = document.getElementById('card-view');

    // Set default view to table
    tableView.classList.remove('hidden');
    tableViewBtn.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]');
    tableViewBtn.classList.remove('border-transparent');

    tableViewBtn.addEventListener('click', function() {
        // Show table view
        tableView.classList.remove('hidden');
        cardView.classList.add('hidden');
        
        // Update button styles
        tableViewBtn.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]');
        tableViewBtn.classList.remove('border-transparent');
        cardViewBtn.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]');
        cardViewBtn.classList.add('border-transparent');
    });

    cardViewBtn.addEventListener('click', function() {
        // Show card view
        cardView.classList.remove('hidden');
        tableView.classList.add('hidden');
        
        // Update button styles
        cardViewBtn.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]');
        cardViewBtn.classList.remove('border-transparent');
        tableViewBtn.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]');
        tableViewBtn.classList.add('border-transparent');
    });
});
</script>

<!-- Custom CSS for animations -->
<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-in-out;
}

.animate-slideInUp {
    animation: slideInUp 0.6s ease-out forwards;
}
</style>

@endsection