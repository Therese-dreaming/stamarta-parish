@extends('layouts.ministry')

@section('title', 'Budget Timeline Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Enhanced Header Section -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/80 rounded-2xl shadow-xl overflow-hidden">
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
                        <h1 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-timeline mr-3"></i>
                            Budget Timeline
                        </h1>
                        <p class="text-white/90 text-base">{{ $ministry->name }} - Detailed Financial History</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-building mr-2"></i>
                            <span>{{ $ministry->name }} Ministry</span>
                        </div>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold mb-1 text-white">
                        {{ $timeline->count() }}
                    </div>
                    <div class="text-sm opacity-90">Total Transactions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('ministry.budget-management.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Budget Overview
                </a>
                
                <a href="{{ route('ministry.activities.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors shadow-lg hover:shadow-xl">
                    <i class="fas fa-calendar mr-2"></i>
                    View Activities
                </a>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#0d5c2f]/10 text-[#0d5c2f]">
                    <i class="fas fa-chart-line w-3 h-3 mr-1"></i>
                    Timeline View
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Timeline Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Timeline Overview Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-timeline mr-2 text-[#0d5c2f]"></i>
                        Financial Timeline
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($timeline->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($timeline as $index => $item)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($index !== $timeline->count() - 1)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    @if($item['type'] === 'credit')
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-green-500 rounded-full flex items-center justify-center ring-8 ring-white shadow-lg">
                                                                <i class="fas fa-plus text-white text-xs"></i>
                                                            </div>
                                                        </div>
                                                    @elseif($item['type'] === 'debit')
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-red-500 rounded-full flex items-center justify-center ring-8 ring-white shadow-lg">
                                                                <i class="fas fa-minus text-white text-xs"></i>
                                                            </div>
                                                        </div>
                                                    @elseif($item['type'] === 'budget_request')
                                                        <div class="relative px-1">
                                                            @php
                                                                $colorClass = match($item['color']) {
                                                                    'green' => 'bg-green-500',
                                                                    'yellow' => 'bg-yellow-500',
                                                                    'red' => 'bg-red-500',
                                                                    default => 'bg-gray-500'
                                                                };
                                                            @endphp
                                                            <div class="h-8 w-8 {{ $colorClass }} rounded-full flex items-center justify-center ring-8 ring-white shadow-lg">
                                                                <i class="fas fa-file-invoice text-white text-xs"></i>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div class="flex-1">
                                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                                            <p class="text-sm text-gray-900 font-medium mb-2">
                                                                {{ $item['description'] }}
                                                            </p>
                                                            @if(isset($item['status']))
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                    @if($item['status'] === 'approved') bg-green-100 text-green-800
                                                                    @elseif($item['status'] === 'pending') bg-yellow-100 text-yellow-800
                                                                    @elseif($item['status'] === 'rejected') bg-red-100 text-red-800
                                                                    @else bg-gray-100 text-gray-800 @endif">
                                                                    {{ ucfirst($item['status']) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap">
                                                        <div class="bg-white rounded-lg p-3 border border-gray-100 shadow-sm">
                                                            <time datetime="{{ $item['date']->format('Y-m-d') }}" class="text-gray-500 text-xs">
                                                                {{ $item['date']->format('M d, Y') }}
                                                            </time>
                                                            <div class="text-lg font-bold mt-1">
                                                                @if($item['type'] === 'credit')
                                                                    <span class="text-green-600">+₱{{ number_format($item['amount'], 2) }}</span>
                                                                @elseif($item['type'] === 'debit')
                                                                    <span class="text-red-600">-₱{{ number_format($item['amount'], 2) }}</span>
                                                                @else
                                                                    <span class="text-gray-600">₱{{ number_format($item['amount'], 2) }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Timeline Data</h3>
                            <p class="text-gray-500 mb-6">No budget timeline data available for this ministry.</p>
                            <a href="{{ route('ministry.activities.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Create Activity
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Sticky -->
        <div class="lg:col-span-1 space-y-6 sticky top-6 h-fit">
            <!-- Ministry Summary -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-building mr-2 text-[#0d5c2f]"></i>
                        Ministry Summary
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-[#0d5c2f]/5 rounded-xl p-4 text-center">
                            <span class="text-2xl font-bold text-[#0d5c2f]">{{ $timeline->count() }}</span>
                            <p class="text-xs text-gray-600 mt-1">Total Transactions</p>
                        </div>
                        <div class="bg-[#0d5c2f]/5 rounded-xl p-4 text-center">
                            <span class="text-2xl font-bold text-[#0d5c2f]">{{ $budgetRequests->count() }}</span>
                            <p class="text-xs text-gray-600 mt-1">Budget Requests</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center">
                                <i class="fas fa-building text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-700">Ministry</span>
                            </div>
                            <span class="text-sm font-medium">{{ $ministry->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-700">Activities</span>
                            </div>
                            <span class="text-sm font-medium">{{ $activities->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-700">Requests</span>
                            </div>
                            <span class="text-sm font-medium">{{ $budgetRequests->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Requests -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-file-invoice mr-2 text-[#0d5c2f]"></i>
                        Recent Budget Requests
                    </h3>
                </div>
                <div class="p-4">
                    @if($budgetRequests->count() > 0)
                        <div class="space-y-3">
                            @foreach($budgetRequests->take(5) as $request)
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">
                                            {{ $request->activity ? $request->activity->title : 'Unknown Activity' }}
                                        </h4>
                                        <span class="text-sm font-bold text-gray-600">₱{{ number_format($request->amount, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($request->status === 'approved') bg-green-100 text-green-800
                                            @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-file-invoice text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">No budget requests found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activities with Budget -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar mr-2 text-[#0d5c2f]"></i>
                        Activities with Budget
                    </h3>
                </div>
                <div class="p-4">
                    @if($activities->count() > 0)
                        <div class="space-y-3">
                            @foreach($activities->take(5) as $activity)
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $activity->title }}</h4>
                                        @if($activity->estimated_budget)
                                            <span class="text-sm font-bold text-gray-600">₱{{ number_format($activity->estimated_budget, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">{{ $activity->start_at->format('M d, Y') }}</span>
                                        @if($activity->pendingBudgetRequest)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($activity->approvedBudgetRequest)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-calendar text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">No activities with budget found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-[#0d5c2f]"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <a href="{{ route('ministry.activities.create') }}" 
                           class="w-full px-4 py-2.5 bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white rounded-lg transition-colors flex items-center justify-center font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Create Activity</span>
                        </a>
                        
                        <a href="{{ route('ministry.activities.index') }}" 
                           class="w-full px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors flex items-center justify-center font-medium">
                            <i class="fas fa-list mr-2"></i>
                            <span>View All Activities</span>
                        </a>
                        
                        <a href="{{ route('ministry.budget-management.index') }}" 
                           class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors flex items-center justify-center font-medium">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>Budget Overview</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection