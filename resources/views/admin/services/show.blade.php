@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Service Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Hero Header -->
    <div class="relative bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/80 rounded-2xl shadow-lg overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="relative px-6 py-6 sm:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/20 mr-2.5">
                            <i class="fas fa-concierge-bell text-white text-base"></i>
                        </span>
                        <h1 class="text-2xl font-bold text-white">{{ $service->name }}</h1>
                    </div>
                    <p class="text-white/80 mt-1.5 ml-10 text-sm">Service ID: #{{ $service->id }}</p>
                </div>
                <div class="flex items-center space-x-2.5">
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ route('admin.services.edit', $service) }}" 
                       class="px-3 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors text-sm">
                        <i class="fas fa-edit mr-2"></i>
                        <span>Edit</span>
                    </a>
                    @endif
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.services.index') : route('admin.services.index') }}" 
                       class="px-3 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="mt-4">
                @if($service->is_active)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        <i class="fas fa-check-circle mr-1"></i>Active
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                        <i class="fas fa-times-circle mr-1"></i>Inactive
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Service Overview Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                        Service Overview
                    </h2>
                    <span class="text-xs text-gray-500">Last updated: {{ $service->updated_at->format('M d, Y') }}</span>
                </div>
                
                <div class="p-4">
                    <!-- Service Details Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex flex-col">
                            <span class="text-xs text-gray-500 mb-1">Duration</span>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-[#0d5c2f] mr-2"></i>
                                <span class="text-base font-medium text-gray-900">{{ $service->formatted_duration }}</span>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex flex-col">
                            <span class="text-xs text-gray-500 mb-1">Capacity</span>
                            <div class="flex items-center">
                                <i class="fas fa-users text-[#0d5c2f] mr-2"></i>
                                <span class="text-base font-medium text-gray-900">{{ $service->max_slots }} slots</span>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex flex-col">
                            <span class="text-xs text-gray-500 mb-1">Fee</span>
                            <div>
                                @php
                                    $fees = $service->fees ?? [];
                                    $byType = [];
                                    foreach ($fees as $k => $f) {
                                        if (is_array($f) && isset($f['type'])) {
                                            $byType[$f['type']] = $f;
                                        } elseif (is_array($f)) {
                                            $byType[$k] = $f;
                                        } else {
                                            $byType[$k] = ['amount' => $f, 'description' => is_string($k) ? ucfirst($k) : 'Fee'];
                                        }
                                    }
                                @endphp
                                @if(isset($byType['regular']))
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700 text-sm">Regular</span>
                                        <span class="text-base font-medium text-gray-900">₱{{ number_format($byType['regular']['amount'] ?? 0, 2) }}</span>
                                    </div>
                                @endif
                                @foreach($byType as $t => $f)
                                    @if(!in_array($t, ['regular']))
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="text-gray-700 text-sm">{{ is_string($t) ? ucfirst($t) : ($f['description'] ?? 'Fee') }}</span>
                                            <span class="text-base font-medium text-gray-900">₱{{ number_format($f['amount'] ?? 0, 2) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    @if($service->description)
                    <div class="mt-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-align-left text-[#0d5c2f] mr-2"></i>Description
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-gray-700 whitespace-pre-line text-sm">{{ $service->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Schedule Information -->
            @if($service->schedules && count($service->schedules) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar mr-2 text-[#0d5c2f]"></i>
                        Available Schedule
                    </h2>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach($service->schedules as $day => $times)
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 hover:shadow-sm transition-all">
                            <div class="flex items-center mb-2.5">
                                <span class="w-7 h-7 rounded-lg bg-[#0d5c2f]/10 flex items-center justify-center mr-2">
                                    <i class="fas fa-calendar-day text-[#0d5c2f] text-sm"></i>
                                </span>
                                <span class="font-medium text-gray-900 capitalize text-sm">{{ $day }}</span>
                            </div>
                            <div class="space-y-1.5 ml-9">
                                @foreach($times as $time)
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-gray-400 text-xs mr-2"></i>
                                    <p class="text-gray-700 text-sm">{{ $time }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Requirements -->
            @if($service->requirements && count($service->requirements) > 0)
            <div id="requirements-card" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list-check mr-2 text-[#0d5c2f]"></i>
                        Requirements
                    </h2>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($service->requirements as $requirement)
                        <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-7 h-7 rounded-lg bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                <i class="fas fa-check text-[#0d5c2f] text-sm"></i>
                            </div>
                            <span class="text-gray-800 text-sm">{{ $requirement }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Quick Stats Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-simple mr-2 text-[#0d5c2f]"></i>
                        Service Statistics
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-[#0d5c2f]/5 rounded-xl p-3 text-center">
                            <span class="text-2xl font-bold text-[#0d5c2f]">{{ $service->bookings()->count() }}</span>
                            <p class="text-xs text-gray-600 mt-1">Total Bookings</p>
                        </div>
                        <div class="bg-[#0d5c2f]/5 rounded-xl p-3 text-center">
                            <span class="text-2xl font-bold text-[#0d5c2f]">{{ $service->bookings()->whereMonth('created_at', now()->month)->count() }}</span>
                            <p class="text-xs text-gray-600 mt-1">This Month</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-plus text-[#0d5c2f] mr-2"></i>
                                <span class="text-xs text-gray-700">Created</span>
                            </div>
                            <span class="text-sm font-medium">{{ $service->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-[#0d5c2f] mr-2"></i>
                                <span class="text-xs text-gray-700">Last Updated</span>
                            </div>
                            <span class="text-sm font-medium">{{ $service->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                <i class="fas fa-id-card text-[#0d5c2f] mr-2"></i>
                                <span class="text-xs text-gray-700">Service ID</span>
                            </div>
                            <span class="text-sm font-medium">#{{ $service->id }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            @if($service->bookings()->count() > 0)
            <div id="recent-bookings-card" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-history mr-2 text-[#0d5c2f]"></i>
                        Recent Bookings
                    </h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @foreach($service->bookings()->latest()->take(5)->get() as $booking)
                        <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0d5c2f]/30 transition-colors">
                            <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center mr-3 overflow-hidden">
                                @if($booking->user->profile_photo_path)
                                    <img src="{{ Storage::url($booking->user->profile_photo_path) }}" alt="{{ $booking->user->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-gray-400 text-sm"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $booking->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium 
                                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                @elseif($booking->status === 'approved') bg-green-100 text-green-800 border border-green-200
                                @elseif($booking->status === 'completed') bg-blue-100 text-blue-800 border border-blue-200
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 border border-red-200
                                @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($service->bookings()->count() > 5)
                    <div class="mt-3 text-center">
                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.index', ['service_id' => $service->id]) : route('admin.bookings.index', ['service_id' => $service->id]) }}" 
                           class="inline-flex items-center text-sm text-[#0d5c2f] hover:text-[#0d5c2f]/80">
                            View all bookings
                            <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    @else
                    <div class="mt-3 text-center">
                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 mb-2">
                            <i class="fas fa-ellipsis-h text-gray-400 text-xs"></i>
                        </div>
                        <p class="text-gray-500 text-xs">No more recent bookings</p>
                        <div class="mt-2 grid grid-cols-3 gap-2 w-full max-w-xs mx-auto">
                            <div class="h-2 bg-gray-100 rounded"></div>
                            <div class="h-2 bg-gray-100 rounded"></div>
                            <div class="h-2 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div id="recent-bookings-card" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900">Recent Bookings</h3>
                </div>
                <div class="p-6 h-full">
                    <div class="h-full flex flex-col items-center justify-center text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#0d5c2f]/10 mb-3">
                            <i class="fas fa-calendar-xmark text-[#0d5c2f] text-xl"></i>
                        </div>
                        <h4 class="text-gray-900 font-medium mb-1">No Bookings Yet</h4>
                        <p class="text-gray-500 text-sm max-w-[220px]">New bookings for this service will appear here.</p>
                        <div class="mt-4 grid grid-cols-3 gap-2 w-full max-w-xs">
                            <div class="h-2 bg-gray-100 rounded"></div>
                            <div class="h-2 bg-gray-100 rounded"></div>
                            <div class="h-2 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>
@endsection