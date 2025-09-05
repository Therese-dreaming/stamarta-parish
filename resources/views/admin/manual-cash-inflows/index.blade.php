@extends('layouts.admin')

@section('title', 'Manual Cash Inflows')

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
                            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-coins mr-3"></i>
                            Manual Cash Inflows
                        </h2>
                        <p class="text-white/90 text-base">Track and manage parish financial resources</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Manage cash inflows from diocese, donations, and other sources</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end text-white">
                    <div class="text-4xl font-bold mb-1">₱{{ number_format($cashInflows->sum('amount'), 2) }}</div>
                    <div class="text-sm opacity-90 mb-4">Total Cash Inflows</div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.manual-cash-inflows.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-plus mr-2"></i>
                            Add Cash Inflow
                        </a>
                        <a href="{{ route('admin.budget-management.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-chart-line mr-2"></i>
                            Budget Overview
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Pending Cash Inflows -->
        <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Pending</p>
                    <p class="text-xl font-bold text-gray-900">{{ $cashInflows->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-clock text-white text-sm"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-info-circle text-yellow-500 mr-1"></i>
                    <span>Awaiting approval</span>
                </div>
            </div>
        </div>

        <!-- Approved Cash Inflows -->
        <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Approved</p>
                    <p class="text-xl font-bold text-gray-900">{{ $cashInflows->where('status', 'approved')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-check-circle text-white text-sm"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-down text-green-500 mr-1"></i>
                    <span>Added to budget</span>
                </div>
            </div>
        </div>

        <!-- Rejected Cash Inflows -->
        <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Rejected</p>
                    <p class="text-xl font-bold text-gray-900">{{ $cashInflows->where('status', 'rejected')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-times-circle text-white text-sm"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i>
                    <span>Not approved</span>
                </div>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Amount</p>
                    <p class="text-xl font-bold text-green-600">₱{{ number_format($cashInflows->sum('amount'), 2) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-peso-sign text-white text-sm"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-chart-line text-purple-500 mr-1"></i>
                    <span>All cash inflows</span>
                </div>
            </div>
        </div>
    </div>

    <!-- View Tabs -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100">
        <div class="p-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-eye mr-2 text-[#0d5c2f]"></i>
                    View Options
                </h3>
                <div class="flex space-x-1">
                    <button id="tableTab" class="px-3 py-1.5 text-xs font-medium rounded-md bg-[#0d5c2f] text-white transition-all duration-200">
                        <i class="fas fa-table mr-1"></i>
                        Table View
                    </button>
                    <button id="cardTab" class="px-3 py-1.5 text-xs font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 transition-all duration-200">
                        <i class="fas fa-th-large mr-1"></i>
                        Card View
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100">
        <div class="p-3 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-2 text-[#0d5c2f]"></i>
                Filters & Search
            </h3>
            <p class="text-xs text-gray-600 mt-1">Refine your search with specific criteria</p>
        </div>
        <div class="p-3">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-2">
                <div>
                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-md focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-all duration-200 text-xs py-1.5">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <div>
                    <label for="source_type" class="block text-xs font-medium text-gray-700 mb-1">Source Type</label>
                    <select name="source_type" id="source_type" class="w-full border-gray-300 rounded-md focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-all duration-200 text-xs py-1.5">
                        <option value="">All Sources</option>
                        <option value="diocese" {{ request('source_type') === 'diocese' ? 'selected' : '' }}>Diocese</option>
                        <option value="donation" {{ request('source_type') === 'donation' ? 'selected' : '' }}>Donation</option>
                        <option value="other" {{ request('source_type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div>
                    <label for="ministry_id" class="block text-xs font-medium text-gray-700 mb-1">Ministry</label>
                    <select name="ministry_id" id="ministry_id" class="w-full border-gray-300 rounded-md focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-all duration-200 text-xs py-1.5">
                        <option value="">All Ministries</option>
                        @foreach($ministries as $ministry)
                            <option value="{{ $ministry->id }}" {{ request('ministry_id') == $ministry->id ? 'selected' : '' }}>
                                {{ $ministry->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="w-full border-gray-300 rounded-md focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-all duration-200 text-xs py-1.5">
                </div>
                
                <div>
                    <label for="date_to" class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                           class="w-full border-gray-300 rounded-md focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-all duration-200 text-xs py-1.5">
                </div>
                
                <div class="flex flex-col justify-end space-y-1">
                    <button type="submit" class="w-full px-2 py-1.5 bg-[#0d5c2f] hover:bg-[#0a4a26] text-white font-medium rounded-md transition-all duration-200 text-xs">
                        <i class="fas fa-search mr-1"></i>
                        Apply
                    </button>
                    <a href="{{ route('admin.manual-cash-inflows.index') }}" class="w-full px-2 py-1.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition-all duration-200 text-center text-xs">
                        <i class="fas fa-refresh mr-1"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Cash Inflows Table View -->
    <div id="tableView" class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list mr-2 text-[#0d5c2f]"></i>
                Cash Inflows List
            </h3>
            <p class="text-sm text-gray-600 mt-1">All manual cash inflow transactions</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ministry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entered By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($cashInflows as $cashInflow)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-[#0d5c2f] rounded-full flex items-center justify-center">
                                        <i class="fas fa-receipt text-white text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $cashInflow->reference_no }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-bold text-green-600">₱{{ number_format($cashInflow->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $cashInflow->description }}">
                                    {{ $cashInflow->description }}
                                </div>
                                @if($cashInflow->source_details)
                                    <div class="text-xs text-gray-500 mt-1">{{ $cashInflow->source_details }}</div>
                                @endif
                                @if($cashInflow->source_type === 'other' && $cashInflow->other_source_specify)
                                    <div class="text-xs text-purple-600 mt-1 font-medium">
                                        <i class="fas fa-tag mr-1"></i>{{ $cashInflow->other_source_specify }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cashInflow->getSourceTypeBadgeClass() }}">
                                    {{ $cashInflow->getSourceTypeLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">
                                    {{ $cashInflow->ministry ? $cashInflow->ministry->name : 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cashInflow->getStatusBadgeClass() }}">
                                    {{ ucfirst($cashInflow->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $cashInflow->enteredBy->name }}</div>
                                <div class="text-xs text-gray-500">{{ $cashInflow->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $cashInflow->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $cashInflow->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.manual-cash-inflows.show', ['manual_cash_inflow' => $cashInflow->id]) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                    @if($cashInflow->isPending())
                                        <a href="{{ route('admin.manual-cash-inflows.edit', ['manual_cash_inflow' => $cashInflow->id]) }}" 
                                           class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-money-bill-wave text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">No cash inflows found</h3>
                                    <p class="text-sm text-gray-500">Create your first manual cash inflow to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($cashInflows->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $cashInflows->links() }}
            </div>
        @endif
    </div>

    <!-- Cash Inflows Card View -->
    <div id="cardView" class="hidden space-y-4">
        @forelse($cashInflows as $cashInflow)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">
                <div class="p-4">
                    <div class="flex flex-col">
                        <!-- Header Section -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-receipt text-white text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">{{ $cashInflow->reference_no }}</h3>
                                    <p class="text-xs text-gray-500">Entered {{ $cashInflow->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $cashInflow->getStatusBadgeClass() }}">
                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                    {{ ucfirst($cashInflow->status) }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $cashInflow->getSourceTypeBadgeClass() }}">
                                    {{ $cashInflow->getSourceTypeLabel() }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                            <div>
                                <p class="text-xs font-medium text-gray-600 mb-1">Amount</p>
                                <p class="text-lg font-bold text-green-600">₱{{ number_format($cashInflow->amount, 2) }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs font-medium text-gray-600 mb-1">Source</p>
                                <p class="text-xs text-gray-900">{{ $cashInflow->getSourceTypeLabel() }}</p>
                                @if($cashInflow->source_type === 'other' && $cashInflow->other_source_specify)
                                    <p class="text-xs text-purple-600 font-medium">{{ $cashInflow->other_source_specify }}</p>
                                @endif
                            </div>
                            
                            <div>
                                <p class="text-xs font-medium text-gray-600 mb-1">Ministry</p>
                                <p class="text-xs text-gray-900">{{ $cashInflow->ministry ? $cashInflow->ministry->name : 'General' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs font-medium text-gray-600 mb-1">Entered By</p>
                                <p class="text-xs text-gray-900">{{ $cashInflow->enteredBy->name }}</p>
                            </div>
                        </div>
                        
                        <!-- Description Section -->
                        @if($cashInflow->description)
                            <div class="mb-3 p-2 bg-gray-50 rounded-md">
                                <p class="text-xs text-gray-700">{{ $cashInflow->description }}</p>
                                @if($cashInflow->source_details)
                                    <p class="text-xs text-gray-500 mt-1">{{ $cashInflow->source_details }}</p>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Actions Section -->
                        <div class="flex flex-col sm:flex-row gap-2 pt-2 border-t border-gray-100">
                            <a href="{{ route('admin.manual-cash-inflows.show', ['manual_cash_inflow' => $cashInflow->id]) }}" 
                               class="inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 font-medium rounded-md transition-colors duration-200 text-xs">
                                <i class="fas fa-eye mr-1"></i>
                                View Details
                            </a>
                            
                            @if($cashInflow->isPending())
                                <a href="{{ route('admin.manual-cash-inflows.edit', ['manual_cash_inflow' => $cashInflow->id]) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-medium rounded-md transition-colors duration-200 text-xs">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-money-bill-wave text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">No cash inflows found</h3>
                <p class="text-sm text-gray-600 mb-4">Get started by adding your first manual cash inflow to track parish finances.</p>
                <a href="{{ route('admin.manual-cash-inflows.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] hover:bg-[#0a4a26] text-white font-semibold rounded-md shadow-md hover:shadow-lg transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Add First Cash Inflow
                </a>
            </div>
        @endforelse

        <!-- Pagination for Card View -->
        @if($cashInflows->hasPages())
            <div class="flex justify-center">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 px-4 py-3">
                    {{ $cashInflows->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableTab = document.getElementById('tableTab');
    const cardTab = document.getElementById('cardTab');
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');

    // Function to switch views
    function switchToTable() {
        tableView.classList.remove('hidden');
        cardView.classList.add('hidden');
        tableTab.classList.remove('bg-gray-200', 'text-gray-700');
        tableTab.classList.add('bg-[#0d5c2f]', 'text-white');
        cardTab.classList.remove('bg-[#0d5c2f]', 'text-white');
        cardTab.classList.add('bg-gray-200', 'text-gray-700');
    }

    function switchToCard() {
        cardView.classList.remove('hidden');
        tableView.classList.add('hidden');
        cardTab.classList.remove('bg-gray-200', 'text-gray-700');
        cardTab.classList.add('bg-[#0d5c2f]', 'text-white');
        tableTab.classList.remove('bg-[#0d5c2f]', 'text-white');
        tableTab.classList.add('bg-gray-200', 'text-gray-700');
    }

    // Event listeners
    tableTab.addEventListener('click', switchToTable);
    cardTab.addEventListener('click', switchToCard);

    // Initialize with table view active
    switchToTable();
});
</script>
@endsection 