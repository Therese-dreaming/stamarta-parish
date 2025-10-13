@extends('layouts.priest')

@section('title', 'Ministry Activities')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Modern Header with Enhanced Stats -->
    <div class="bg-gradient-to-br from-[#0d5c2f] via-[#0d5c2f]/95 to-[#0d5c2f]/80 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <!-- Decorative Elements -->
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-bl-full"></div>
            <div class="absolute right-8 top-8 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="absolute right-16 bottom-8 w-8 h-8 bg-white/15 rounded-full"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-white/5 rounded-tr-full"></div>
            
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center relative z-10 space-y-6 lg:space-y-0">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg mr-6">
                        <i class="fas fa-calendar-star text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Ministry Activities</h1>
                        <div class="flex items-center space-x-4">
                            <p class="text-white/80 text-sm">Activity Management • Read Only Access</p>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-400 rounded-full shadow-lg animate-pulse mr-2"></div>
                                <span class="text-blue-200 text-sm font-medium">{{ $requests->total() }} Total</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Header Stats -->
                <div class="flex flex-wrap gap-4">
                    <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[140px] text-center">
                        <div class="text-white/80 text-xs uppercase tracking-wide font-medium">Total Activities</div>
                        <div class="text-white text-2xl font-bold">{{ $requests->total() }}</div>
                    </div>
                    <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[140px] text-center">
                        <div class="text-white/80 text-xs uppercase tracking-wide font-medium">Total Budget</div>
                        <div class="text-white text-2xl font-bold">₱{{ number_format($requests->sum('amount'), 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl shadow-lg border border-yellow-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-yellow-800 mb-2">Pending Activities</p>
                    <p class="text-3xl font-bold text-yellow-900">{{ $statusCounts['pending'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-lg border border-green-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-800 mb-2">Approved Activities</p>
                    <p class="text-3xl font-bold text-green-900">{{ $statusCounts['approved'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow-lg border border-red-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-2">Rejected Activities</p>
                    <p class="text-3xl font-bold text-red-900">{{ $statusCounts['rejected'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all">
                    <i class="fas fa-times-circle text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg border border-blue-200 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-800 mb-2">Completed Activities</p>
                    <p class="text-3xl font-bold text-blue-900">{{ $statusCounts['completed'] }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all">
                    <i class="fas fa-flag-checkered text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Activities List -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <div class="w-8 h-8 bg-[#0d5c2f] rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-list text-white text-sm"></i>
                </div>
                Ministry Activities
            </h2>
            <p class="text-sm text-gray-600 mt-1">View and manage ministry activity requests</p>
        </div>

        @if($requests->count() > 0)
            <div class="overflow-x-auto">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ministry</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Budget</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($requests as $request)
                            <tr class="hover:bg-gray-50/50 transition-all duration-200 group">
                                <td class="px-6 py-5">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/70 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                                <i class="fas fa-calendar-star text-white text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-base font-semibold text-gray-900">{{ $request->title }}</div>
                                            <div class="text-sm text-gray-500 max-w-xs truncate">{{ $request->description ?: 'No description available' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                                <i class="fas fa-users text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $request->ministry->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $request->ministry->head->name ?? 'No head assigned' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="inline-flex items-center px-3 py-2 bg-green-100 text-green-800 rounded-xl font-semibold text-sm shadow-sm">
                                        <i class="fas fa-peso-sign mr-1 text-xs"></i>
                                        {{ number_format($request->amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $request->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'approved' => 'bg-green-100 text-green-800 border-green-200',
                                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                            'completed' => 'bg-blue-100 text-blue-800 border-blue-200'
                                        ];
                                        $statusIcons = [
                                            'pending' => 'fas fa-clock',
                                            'approved' => 'fas fa-check-circle',
                                            'rejected' => 'fas fa-times-circle',
                                            'completed' => 'fas fa-flag-checkered'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border shadow-sm {{ $statusClasses[$request->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        <i class="{{ $statusIcons[$request->status] ?? 'fas fa-question-circle' }} mr-2 text-xs"></i>
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('priest.ministries.ministry-activities.show', $request) }}" 
                                           class="w-10 h-10 bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg"
                                           title="View Activity Details">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $requests->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-calendar-star text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Activities Found</h3>
                <p class="text-gray-500 max-w-md mx-auto text-lg leading-relaxed">
                    There are currently no ministry activities to display. Activities will appear here once ministries submit their requests.
                </p>
            </div>
        @endif
    </div>
</div>

<style>
/* Enhanced animations and transitions */
.animate-fadeIn {
    animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Enhanced hover effects */
.group:hover .group-hover\:shadow-xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Pulse animation for active status */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Enhanced button hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

.hover\:-translate-y-2:hover {
    transform: translateY(-8px);
}

/* Backdrop blur support */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Glass effect for stats cards */
.bg-white\/15 {
    background-color: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Enhanced shadow effects */
.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.hover\:shadow-2xl:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Group hover effects for icons */
.group-hover\:scale-110:hover {
    transform: scale(1.1);
}
</style>
@endsection
