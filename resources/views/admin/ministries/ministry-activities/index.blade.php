@extends('layouts.admin')

@section('title', 'Ministry Activities')

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
                            <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-chart-line mr-3"></i>
                            Ministry Activities
                        </h1>
                        <p class="text-white/90 text-base">Review and manage ministry activities</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Total: {{ $requests->total() }} requests</span>
                        </div>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold mb-1">₱{{ number_format($requests->sum('amount'), 0) }}</div>
                    <div class="text-sm opacity-90">Total Requested Amount</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending Requests</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-yellow-500 mr-1"></i>
                    <span>Awaiting review</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span>Funds allocated</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-times text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                    <span>Not approved</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Avg. Amount</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ $requests->count() > 0 ? number_format($requests->avg('amount'), 0) : '0' }}</p>
                </div>
                <div class="w-12 h-12 bg-[#0d5c2f] rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-peso-sign text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-chart-bar text-[#0d5c2f] mr-1"></i>
                    <span>Per request</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl flex items-center shadow-sm">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-check text-white text-sm"></i>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl flex items-center shadow-sm">
            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
            </div>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Enhanced Filter Tabs -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-2 text-[#0d5c2f]"></i>
                Filter Requests
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
                    Showing {{ $requests->count() }} of {{ $requests->total() }} requests
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.ministries.ministry-activities.index', ['view' => request('view', 'table')]) }}" 
               class="group px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == null ? 'bg-[#0d5c2f] text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md' }}">
                <div class="flex items-center">
                    <i class="fas fa-list mr-2 {{ request('status') == null ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}"></i>
                    <span>All Requests</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('status') == null ? 'bg-white/20' : 'bg-gray-200' }}">{{ $requests->total() }}</span>
                </div>
            </a>
            <a href="{{ route('admin.ministries.ministry-activities.index', ['status' => 'pending', 'view' => request('view', 'table')]) }}" 
               class="group px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == 'pending' ? 'bg-[#0d5c2f] text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md' }}">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-2 {{ request('status') == 'pending' ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}"></i>
                    <span>Pending</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('status') == 'pending' ? 'bg-white/20' : 'bg-gray-200' }}">{{ $statusCounts['pending'] }}</span>
                </div>
            </a>
            <a href="{{ route('admin.ministries.ministry-activities.index', ['status' => 'approved', 'view' => request('view', 'table')]) }}" 
               class="group px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == 'approved' ? 'bg-[#0d5c2f] text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md' }}">
                <div class="flex items-center">
                    <i class="fas fa-check mr-2 {{ request('status') == 'approved' ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}"></i>
                    <span>Approved</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('status') == 'approved' ? 'bg-white/20' : 'bg-gray-200' }}">{{ $statusCounts['approved'] }}</span>
                </div>
            </a>
            <a href="{{ route('admin.ministries.ministry-activities.index', ['status' => 'rejected', 'view' => request('view', 'table')]) }}" 
               class="group px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == 'rejected' ? 'bg-[#0d5c2f] text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md' }}">
                <div class="flex items-center">
                    <i class="fas fa-times mr-2 {{ request('status') == 'rejected' ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}"></i>
                    <span>Rejected</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('status') == 'rejected' ? 'bg-white/20' : 'bg-gray-200' }}">{{ $statusCounts['rejected'] }}</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Cards View -->
    <div id="cards-view-content" class="view-content {{ request('view', 'table') == 'cards' ? '' : 'hidden' }}">
        @if($requests->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($requests as $req)
                    <div class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <!-- Status Header -->
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-800', 'icon' => 'fa-clock', 'border' => 'border-yellow-200'],
                                'approved' => ['bg' => 'bg-green-500', 'text' => 'text-green-800', 'icon' => 'fa-check', 'border' => 'border-green-200'],
                                'rejected' => ['bg' => 'bg-red-500', 'text' => 'text-red-800', 'icon' => 'fa-times', 'border' => 'border-red-200'],
                                'complete' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-800', 'icon' => 'fa-check-circle', 'border' => 'border-blue-200']
                            ];
                            $config = $statusConfig[$req->status] ?? $statusConfig['pending'];
                        @endphp
                        <div class="{{ $config['bg'] }} px-6 py-4 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                                        <i class="fas fa-file-invoice-dollar text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold truncate">{{ $req->purpose }}</h3>
                                        <p class="text-sm opacity-90">Request #{{ $req->id }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold">₱{{ number_format($req->amount, 0) }}</div>
                                    <div class="text-xs opacity-90">Requested</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Ministry & Requester Info -->
                            <div class="mb-6">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-[#0d5c2f] rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-church text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $req->ministry->name }}</p>
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $req->requestedBy->name ?? 'Unknown' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Info -->
                            @if($req->activity)
                                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-calendar-alt text-[#0d5c2f] mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-900">{{ $req->activity->title }}</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center text-xs text-gray-600">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span>{{ $req->activity->start_at->format('M d, Y \a\t g:i A') }}</span>
                                        </div>
                                        @if($req->activity->end_at)
                                        <div class="flex items-center text-xs text-gray-600">
                                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                            <span>{{ $req->activity->end_at->format('M d, Y \a\t g:i A') }}</span>
                                        </div>
                                        @endif
                                        @if($req->activity->location)
                                        <div class="flex items-center text-xs text-gray-600">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                            <span>{{ $req->activity->location }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Request Details -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Submitted
                                    </div>
                                    <div class="text-xs font-medium text-gray-900">
                                        {{ $req->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mb-6">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium {{ $config['bg'] }} text-white shadow-sm">
                                    <i class="fas {{ $config['icon'] }} mr-1.5"></i>
                                    {{ ucfirst($req->status) }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.ministries.ministry-activities.show', $req) }}" 
                                   class="group inline-flex items-center text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium transition-colors">
                                    <span>View Details</span>
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                </a>
                                @if($req->status === 'pending')
                                    <div class="flex items-center space-x-2">
                                        <button onclick="openApproveModal({{ $req->id }}, '{{ $req->purpose }}', '{{ $req->ministry->name }}', {{ $req->amount }})"
                                                class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-check mr-1.5"></i>
                                            Approve
                                        </button>
                                        <button onclick="openRejectModal({{ $req->id }}, '{{ $req->purpose }}', '{{ $req->ministry->name }}')"
                                                class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-times mr-1.5"></i>
                                            Reject
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Table View -->
    <div id="table-view-content" class="view-content {{ request('view', 'table') == 'table' ? '' : 'hidden' }}">
        @if($requests->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Request Details
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ministry
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Activity
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Start Date
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    End Date
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $req)
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                                        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times'],
                                        'complete' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-check-circle']
                                    ];
                                    $config = $statusConfig[$req->status] ?? $statusConfig['pending'];
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $req->purpose }}</div>
                                            <div class="text-sm text-gray-500">#{{ $req->id }}</div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $req->requestedBy->name ?? 'Unknown' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-[#0d5c2f] rounded-md flex items-center justify-center mr-2">
                                                <i class="fas fa-church text-white text-xs"></i>
                                            </div>
                                            <span class="text-sm text-gray-900">{{ $req->ministry->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($req->activity)
                                            <div class="text-sm text-gray-900">{{ $req->activity->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $req->activity->start_at->format('M d, Y') }}</div>
                                        @else
                                            <span class="text-sm text-gray-400">No activity</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">₱{{ number_format($req->amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                            <i class="fas {{ $config['icon'] }} mr-1"></i>
                                            {{ ucfirst($req->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($req->activity && $req->activity->start_at)
                                            <div class="text-sm text-gray-900">{{ $req->activity->start_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $req->activity->start_at->format('g:i A') }}</div>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($req->activity && $req->activity->end_at)
                                            <div class="text-sm text-gray-900">{{ $req->activity->end_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $req->activity->end_at->format('g:i A') }}</div>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.ministries.ministry-activities.show', $req) }}" 
                                               class="w-8 h-8 bg-[#0d5c2f] rounded-lg flex items-center justify-center text-white hover:bg-[#0d5c2f]/80 transition-colors"
                                               title="View Details">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            @if($req->status === 'pending')
                                                <button onclick="openApproveModal({{ $req->id }}, '{{ $req->purpose }}', '{{ $req->ministry->name }}', {{ $req->amount }})"
                                                        class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center text-white hover:bg-green-600 transition-colors"
                                                        title="Approve Request">
                                                    <i class="fas fa-check text-sm"></i>
                                                </button>
                                                <button onclick="openRejectModal({{ $req->id }}, '{{ $req->purpose }}', '{{ $req->ministry->name }}')"
                                                        class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white hover:bg-red-600 transition-colors"
                                                        title="Reject Request">
                                                    <i class="fas fa-times text-sm"></i>
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
        @endif
    </div>

    <!-- Enhanced Pagination -->
    @if($requests->hasPages())
        <div class="mt-8 flex justify-center">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 px-6 py-4">
                {{ $requests->links() }}
            </div>
        </div>
    @endif

    <!-- Empty State -->
    @if($requests->count() == 0)
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-lg">
                <i class="fas fa-file-invoice-dollar text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">No Ministry Activities</h3>
            <p class="text-gray-600 text-lg mb-6">There are no ministry activities to review at this time.</p>
            <div class="text-center">
                <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Ministry activities will appear here when ministries submit them for approval.</p>
            </div>
        </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="bg-green-700 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-check text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Approve Ministry Activity</h3>
                </div>
                <button type="button" onclick="closeApproveModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <form id="approve-form" action="" method="POST">
                @csrf
                
                <!-- Confirmation Message -->
                <div class="mb-6">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            <span class="text-sm font-medium text-green-800">Confirmation Required</span>
                        </div>
                        <p class="text-sm text-green-700">You are about to approve the following ministry activity. This action will credit the ministry's funds and cannot be undone.</p>
                    </div>
                    
                    <!-- Activity Details -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-alt text-gray-600 mr-2"></i>
                            Activity Details
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Purpose:</span>
                                <span class="text-sm font-medium text-gray-900" id="approvePurpose"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Ministry:</span>
                                <span class="text-sm font-medium text-gray-900" id="approveMinistryName"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Amount:</span>
                                <span class="text-sm font-bold text-green-600" id="approveAmount"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeApproveModal()" 
                            class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-green-700 text-white rounded-xl hover:bg-green-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                        <i class="fas fa-check mr-2"></i>
                        Confirm Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="bg-red-700 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-times text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Reject Ministry Activity</h3>
                </div>
                <button type="button" onclick="closeRejectModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <form id="reject-form" action="" method="POST">
                @csrf
                
                <!-- Warning Message -->
                <div class="mb-6">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                            <span class="text-sm font-medium text-red-800">Action Required</span>
                        </div>
                        <p class="text-sm text-red-700">You are about to reject this ministry activity. Please provide a clear reason for the rejection.</p>
                    </div>
                    
                    <!-- Activity Details -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-alt text-gray-600 mr-2"></i>
                            Activity Details
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Purpose:</span>
                                <span class="text-sm font-medium text-gray-900" id="reject-purpose"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Ministry:</span>
                                <span class="text-sm font-medium text-gray-900" id="reject-ministry"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Rejection Reason -->
                <div class="mb-6">
                    <label for="rejection_notes" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-comment-alt text-gray-600 mr-2"></i>
                        Rejection Reason <span class="text-red-500 font-bold">*</span>
                    </label>
                    <textarea id="rejection_notes" name="rejection_notes" rows="3" required
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-colors resize-none"
                              placeholder="Please provide a detailed reason for rejecting this activity..."></textarea>
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                        This field is required and will be shared with the ministry.
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-700 text-white rounded-xl hover:bg-red-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                        <i class="fas fa-times mr-2"></i>
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// View Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const cardsViewBtn = document.getElementById('cards-view');
    const tableViewBtn = document.getElementById('table-view');
    const cardsViewContent = document.getElementById('cards-view-content');
    const tableViewContent = document.getElementById('table-view-content');

    // Get current URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const currentView = urlParams.get('view') || 'table';
    const currentStatus = urlParams.get('status');

    // Set initial view based on URL parameter
    if (currentView === 'cards') {
        cardsViewBtn.classList.add('bg-white', 'text-gray-700', 'shadow-sm');
        cardsViewBtn.classList.remove('text-gray-500');
        tableViewBtn.classList.add('text-gray-500');
        tableViewBtn.classList.remove('bg-white', 'text-gray-700', 'shadow-sm');
        cardsViewContent.classList.remove('hidden');
        tableViewContent.classList.add('hidden');
    } else {
        tableViewBtn.classList.add('bg-white', 'text-gray-700', 'shadow-sm');
        tableViewBtn.classList.remove('text-gray-500');
        cardsViewBtn.classList.add('text-gray-500');
        cardsViewBtn.classList.remove('bg-white', 'text-gray-700', 'shadow-sm');
        tableViewContent.classList.remove('hidden');
        cardsViewContent.classList.add('hidden');
    }

    function updateURL(view) {
        const newParams = new URLSearchParams(window.location.search);
        newParams.set('view', view);
        if (currentStatus) {
            newParams.set('status', currentStatus);
        }
        const newURL = window.location.pathname + '?' + newParams.toString();
        window.history.pushState({}, '', newURL);
    }

    cardsViewBtn.addEventListener('click', function() {
        // Update button states
        cardsViewBtn.classList.add('bg-white', 'text-gray-700', 'shadow-sm');
        cardsViewBtn.classList.remove('text-gray-500');
        tableViewBtn.classList.add('text-gray-500');
        tableViewBtn.classList.remove('bg-white', 'text-gray-700', 'shadow-sm');

        // Show cards view
        cardsViewContent.classList.remove('hidden');
        tableViewContent.classList.add('hidden');

        // Update URL
        updateURL('cards');
    });

    tableViewBtn.addEventListener('click', function() {
        // Update button states
        tableViewBtn.classList.add('bg-white', 'text-gray-700', 'shadow-sm');
        tableViewBtn.classList.remove('text-gray-500');
        cardsViewBtn.classList.add('text-gray-500');
        cardsViewBtn.classList.remove('bg-white', 'text-gray-700', 'shadow-sm');

        // Show table view
        tableViewContent.classList.remove('hidden');
        cardsViewContent.classList.add('hidden');

        // Update URL
        updateURL('table');
    });
});

function openApproveModal(id, purpose, ministry, amount) {
    document.getElementById('approveMinistryName').textContent = ministry;
    document.getElementById('approvePurpose').textContent = purpose;
    document.getElementById('approveAmount').textContent = amount.toLocaleString('en-US', {minimumFractionDigits: 2});
    
    // Set the correct form action URL
    const form = document.getElementById('approve-form');
    form.action = `/admin/ministries/ministry-activities/${id}/approve`;
    
    document.getElementById('approve-modal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approve-modal').classList.add('hidden');
}

function openRejectModal(id, purpose, ministry) {
    document.getElementById('reject-ministry').textContent = ministry;
    document.getElementById('reject-purpose').textContent = purpose;
    
    // Set the correct form action URL
    const form = document.getElementById('reject-form');
    form.action = `/admin/ministries/ministry-activities/${id}/reject`;
    
    document.getElementById('reject-modal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
    document.getElementById('rejection_notes').value = '';
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const approveModal = document.getElementById('approve-modal');
    const rejectModal = document.getElementById('reject-modal');
    
    if (event.target === approveModal) {
        closeApproveModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
});
</script>
@endpush


