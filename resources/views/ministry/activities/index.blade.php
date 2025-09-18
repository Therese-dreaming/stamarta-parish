@extends('layouts.ministry')

@section('title', 'Activities - ' . $ministry->name)
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
                            <i class="fas fa-calendar-alt text-white text-2xl"></i>
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
                            <i class="fas fa-calendar-alt mr-3"></i>
                            {{ $ministry->name }} Activities
                        </h1>
                        <p class="text-white/90 text-base">Manage your ministry activities and budget planning</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Total: {{ $activities->total() }} activities</span>
                        </div>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold mb-1">₱{{ number_format($activities->sum('estimated_budget'), 0) }}</div>
                    <div class="text-sm opacity-90">Total Budget Requested</div>
                    <div class="flex space-x-3 mt-4">
                        <a href="{{ route('ministry.activities.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-plus mr-2"></i>
                            Add Activity
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
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Activities</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalActivities }}</p>
                </div>
                <div class="w-12 h-12 bg-[#0d5c2f] rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-[#0d5c2f] mr-1"></i>
                    <span>All activities</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Public Events</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $publicActivities }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-globe text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span>Open to public</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Internal Events</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $internalActivities }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-lock text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-blue-500 mr-1"></i>
                    <span>Ministry only</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Avg. Budget</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ $activities->count() > 0 ? number_format($activities->avg('estimated_budget'), 0) : '0' }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-peso-sign text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-chart-bar text-purple-500 mr-1"></i>
                    <span>Per activity</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filter Tabs -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-2 text-[#0d5c2f]"></i>
                Filter Activities
            </h3>
            <div class="flex items-center space-x-4">
                <!-- View Toggle -->
                <div class="flex items-center bg-gray-100 rounded-lg p-1">
                    <button id="cards-view" class="px-3 py-1.5 rounded-md text-sm font-medium transition-all duration-200 {{ request('view', 'table') == 'cards' ? 'bg-white text-gray-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-th-large mr-1.5"></i>
                        Cards
                    </button>
                    <button id="table-view" class="px-3 py-1.5 rounded-md text-sm font-medium transition-all duration-200 {{ request('view', 'table') == 'table' ? 'bg-white text-gray-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-table mr-1.5"></i>
                        Table
                    </button>
                </div>
                <div class="text-sm text-gray-500">
                    Showing {{ $activities->count() }} of {{ $activities->total() }} activities
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('ministry.activities.index', ['view' => request('view', 'table')]) }}" 
               class="group px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request('type') == null ? 'bg-[#0d5c2f] text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md' }}">
                <div class="flex items-center">
                    <i class="fas fa-list mr-2 {{ request('type') == null ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}"></i>
                    <span>All Activities</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('type') == null ? 'bg-white/20' : 'bg-gray-200' }}">{{ $totalActivities }}</span>
                </div>
            </a>
            <a href="{{ route('ministry.activities.index', ['type' => 'public', 'view' => request('view', 'table')]) }}" 
               class="group px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request('type') == 'public' ? 'bg-[#0d5c2f] text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md' }}">
                <div class="flex items-center">
                    <i class="fas fa-globe mr-2 {{ request('type') == 'public' ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}"></i>
                    <span>Public Events</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('type') == 'public' ? 'bg-white/20' : 'bg-gray-200' }}">{{ $publicActivities }}</span>
                </div>
            </a>
            <a href="{{ route('ministry.activities.index', ['type' => 'internal', 'view' => request('view', 'table')]) }}" 
               class="group px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request('type') == 'internal' ? 'bg-[#0d5c2f] text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md' }}">
                <div class="flex items-center">
                    <i class="fas fa-lock mr-2 {{ request('type') == 'internal' ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}"></i>
                    <span>Internal Events</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('type') == 'internal' ? 'bg-white/20' : 'bg-gray-200' }}">{{ $internalActivities }}</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Cards View -->
    <div id="cards-view-content" class="view-content {{ request('view', 'table') == 'cards' ? '' : 'hidden' }}">
        @if($activities->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($activities as $activity)
                    <div class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <!-- Status Header -->
                        <div class="px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#0d5c2f] transition-colors duration-200">{{ $activity->title }}</h3>
                                @php
                                    $budgetStatus = 'draft';
                                    $statusColor = 'gray';
                                    $statusIcon = 'fa-file';
                                    
                                    if ($activity->currentBudgetRequest) {
                                        $budgetStatus = $activity->currentBudgetRequest->status;
                                    } elseif ($activity->pendingBudgetRequest) {
                                        $budgetStatus = $activity->pendingBudgetRequest->status;
                                    } elseif ($activity->approvedBudgetRequest) {
                                        $budgetStatus = $activity->approvedBudgetRequest->status;
                                    }
                                    
                                    switch($budgetStatus) {
                                        case 'pending':
                                            $statusColor = 'yellow';
                                            $statusIcon = 'fa-clock';
                                            break;
                                        case 'approved':
                                            $statusColor = 'green';
                                            $statusIcon = 'fa-check';
                                            break;
                                        case 'complete':
                                            $statusColor = 'blue';
                                            $statusIcon = 'fa-flag-checkered';
                                            break;
                                        case 'rejected':
                                            $statusColor = 'red';
                                            $statusIcon = 'fa-times';
                                            break;
                                        default:
                                            $statusColor = 'gray';
                                            $statusIcon = 'fa-file';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                    <i class="fas {{ $statusIcon }} mr-1"></i>{{ ucfirst($budgetStatus) }}
                                </span>
                            </div>
                            <div class="mt-2">
                                @if($activity->is_public)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700">
                                        <i class="fas fa-globe mr-1"></i>Public Event
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                        <i class="fas fa-lock mr-1"></i>Internal Event
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Activity Details -->
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-calendar-day mr-2 text-[#0d5c2f]"></i>
                                        Date
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $activity->start_at->format('M d, Y') }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>
                                        Time
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">
                                        @if($activity->is_all_day)
                                            All Day
                                        @else
                                            {{ $activity->start_at->format('g:i A') }}
                                            @if($activity->end_at)
                                                - {{ $activity->end_at->format('g:i A') }}
                                            @endif
                                        @endif
                                    </span>
                                </div>

                                @if($activity->location)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-[#0d5c2f]"></i>
                                        Location
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $activity->location }}</span>
                                </div>
                                @endif

                                @if($activity->estimated_budget)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-peso-sign mr-2 text-[#0d5c2f]"></i>
                                        Budget
                                    </span>
                                    <span class="text-sm font-bold text-[#0d5c2f]">₱{{ number_format($activity->estimated_budget, 2) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex items-center justify-end space-x-2">
                                {{-- View Button: Always available --}}
                                <a href="{{ route('ministry.activities.show', $activity) }}" 
                                   class="w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-all duration-200 hover:scale-110" 
                                   title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                {{-- Edit Button: Hide when approved or complete --}}
                                @if($budgetStatus !== 'approved' && $budgetStatus !== 'complete')
                                <a href="{{ route('ministry.activities.edit', $activity) }}" 
                                   class="w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" 
                                   title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                @endif
                                
                                {{-- Delete Button: Hide when complete --}}
                                @if($budgetStatus !== 'complete')
                                <button type="button" 
                                        onclick="openDeleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                        class="w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110" 
                                        title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No activities found</h3>
                <p class="text-gray-600 text-sm mb-6">No activities have been created for this ministry yet.</p>
                <a href="{{ route('ministry.activities.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0a4a26] transition-colors duration-200 font-medium shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Activity
                </a>
            </div>
        @endif
    </div>

    <!-- Table View -->
    <div id="table-view-content" class="view-content {{ request('view', 'table') == 'table' ? '' : 'hidden' }}">
        @if($activities->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-xl bg-[#0d5c2f] flex items-center justify-center">
                                            <i class="fas fa-calendar text-white text-sm"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($activity->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $activity->start_at->format("M j, Y") }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @if($activity->is_all_day)
                                            All Day
                                        @else
                                            {{ $activity->start_at->format("g:i A") }}
                                            @if($activity->end_at)
                                                - {{ $activity->end_at->format("g:i A") }}
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $activity->location ?: "—" }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activity->estimated_budget)
                                        <div class="text-sm font-medium text-gray-900">₱{{ number_format($activity->estimated_budget, 2) }}</div>
                                    @else
                                        <span class="text-sm text-gray-500">No budget</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $budgetStatus = 'draft';
                                        $statusColor = 'gray';
                                        $statusIcon = 'fa-file';
                                        
                                        if ($activity->currentBudgetRequest) {
                                            $budgetStatus = $activity->currentBudgetRequest->status;
                                        } elseif ($activity->pendingBudgetRequest) {
                                            $budgetStatus = $activity->pendingBudgetRequest->status;
                                        } elseif ($activity->approvedBudgetRequest) {
                                            $budgetStatus = $activity->approvedBudgetRequest->status;
                                        }
                                        
                                        switch($budgetStatus) {
                                            case 'pending':
                                                $statusColor = 'yellow';
                                                $statusIcon = 'fa-clock';
                                                break;
                                            case 'approved':
                                                $statusColor = 'green';
                                                $statusIcon = 'fa-check';
                                                break;
                                            case 'complete':
                                                $statusColor = 'blue';
                                                $statusIcon = 'fa-flag-checkered';
                                                break;
                                            case 'rejected':
                                                $statusColor = 'red';
                                                $statusIcon = 'fa-times';
                                                break;
                                            default:
                                                $statusColor = 'gray';
                                                $statusIcon = 'fa-file';
                                        }
                                    @endphp
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                            <i class="fas {{ $statusIcon }} mr-1"></i>{{ ucfirst($budgetStatus) }}
                                        </span>
                                        <div class="text-xs text-gray-500">
                                            @if($activity->is_public)
                                                <i class="fas fa-globe mr-1"></i>Public
                                            @else
                                                <i class="fas fa-lock mr-1"></i>Internal
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        {{-- View Button: Always available --}}
                                        <a href="{{ route('ministry.activities.show', $activity) }}" 
                                           class="w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-all duration-200 hover:scale-110" 
                                           title="View">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        {{-- Edit Button: Hide when approved or complete --}}
                                        @if($budgetStatus !== 'approved' && $budgetStatus !== 'complete')
                                        <a href="{{ route('ministry.activities.edit', $activity) }}" 
                                           class="w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" 
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @endif
                                        
                                        {{-- Delete Button: Hide when complete --}}
                                        @if($budgetStatus !== 'complete')
                                        <button type="button" 
                                                onclick="openDeleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                                class="w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110" 
                                                title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No activities found</h3>
                <p class="text-gray-600 text-sm mb-6">No activities have been created for this ministry yet.</p>
                <a href="{{ route('ministry.activities.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-xl hover:bg-[#0a4a26] transition-colors duration-200 font-medium shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Activity
                </a>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($activities->count() > 0)
        <div class="flex items-center justify-between bg-white rounded-xl shadow-lg border border-gray-100 px-6 py-4">
            <div class="text-sm text-gray-500">
                Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} activities
            </div>
            <div>
                {{ $activities->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="bg-red-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Delete Activity</h3>
                </div>
                <button type="button" onclick="closeDeleteModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <span class="text-sm font-medium text-red-800">Action Required</span>
                    </div>
                    <p class="text-sm text-red-700">You are about to delete this ministry activity. This action cannot be undone.</p>
                </div>
                
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-calendar text-gray-600 mr-2"></i>
                        Activity Details
                    </h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Title:</span>
                            <span class="text-sm font-medium text-gray-900" id="deleteActivityTitle"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                        <i class="fas fa-trash mr-2"></i>
                        Confirm Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Delete Modal Functions
    function openDeleteModal(activityId, activityTitle) {
        const modal = document.getElementById('deleteModal');
        const titleSpan = document.getElementById('deleteActivityTitle');
        const form = document.getElementById('deleteForm');
        
        // Set the activity title and form action
        titleSpan.textContent = activityTitle;
        form.action = `/ministry/activities/${activityId}`;
        
        // Show the modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Add backdrop click to close
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Close modal when cancel button is clicked
    document.addEventListener('DOMContentLoaded', function() {
        const cancelBtn = document.getElementById('cancelDelete');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeDeleteModal);
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    });

    // View Toggle Functions
    document.addEventListener("DOMContentLoaded", function() {
        const cardsViewBtn = document.getElementById("cards-view");
        const tableViewBtn = document.getElementById("table-view");
        const cardsViewContent = document.getElementById("cards-view-content");
        const tableViewContent = document.getElementById("table-view-content");
        
        function showCardsView() {
            cardsViewContent.classList.remove("hidden");
            tableViewContent.classList.add("hidden");
            cardsViewBtn.classList.add("bg-white", "text-gray-700", "shadow-sm");
            cardsViewBtn.classList.remove("text-gray-500");
            tableViewBtn.classList.remove("bg-white", "text-gray-700", "shadow-sm");
            tableViewBtn.classList.add("text-gray-500");
        }
        
        function showTableView() {
            tableViewContent.classList.remove("hidden");
            cardsViewContent.classList.add("hidden");
            tableViewBtn.classList.add("bg-white", "text-gray-700", "shadow-sm");
            tableViewBtn.classList.remove("text-gray-500");
            cardsViewBtn.classList.remove("bg-white", "text-gray-700", "shadow-sm");
            cardsViewBtn.classList.add("text-gray-500");
        }
        
        // Event listeners
        if (cardsViewBtn) {
            cardsViewBtn.addEventListener("click", showCardsView);
        }
        if (tableViewBtn) {
            tableViewBtn.addEventListener("click", showTableView);
        }
        
        // Initial view setup
        if (window.innerWidth < 768) {
            // Always show cards on mobile
            showCardsView();
        } else {
            // On desktop, respect user preference
            const savedPreference = localStorage.getItem("ministryActivityViewPreference");
            if (savedPreference === "cards") {
                showCardsView();
            } else {
                showTableView();
            }
        }
        
        // Handle window resize
        window.addEventListener("resize", function() {
            if (window.innerWidth < 768) {
                showCardsView();
            }
        });
    });
</script>
@endsection


