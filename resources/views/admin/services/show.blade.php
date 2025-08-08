@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Service Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $service->name }}</h1>
                    <p class="text-white/80 mt-1">Service Details</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ route('admin.services.edit', $service) }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Edit Service">
                        <i class="fas fa-edit text-lg"></i>
                    </a>
                    @endif
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.services.index') : route('admin.services.index') }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Services">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                        Service Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-tag mr-2 text-[#0d5c2f]"></i>Service Name
                            </label>
                            <p class="text-lg font-medium text-gray-900">{{ $service->name }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-[#0d5c2f]"></i>Status
                            </label>
                            @if($service->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                </span>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>Duration
                            </label>
                            <p class="text-lg text-gray-900">{{ $service->formatted_duration }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-users mr-2 text-[#0d5c2f]"></i>Max Slots
                            </label>
                            <p class="text-lg text-gray-900">{{ $service->max_slots }} slots</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-money-bill mr-2 text-[#0d5c2f]"></i>Fees
                            </label>
                            <p class="text-lg text-gray-900">{{ $service->formatted_fees }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f]"></i>Created
                            </label>
                            <p class="text-lg text-gray-900">{{ $service->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    @if($service->description)
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-align-left mr-2 text-[#0d5c2f]"></i>Description
                        </label>
                        <p class="text-gray-900">{{ $service->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Schedule Information -->
            @if($service->schedules && count($service->schedules) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar mr-2 text-[#0d5c2f]"></i>
                        Schedule
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($service->schedules as $day => $times)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-day text-gray-600 mr-2"></i>
                                <span class="font-medium text-gray-900 capitalize">{{ $day }}</span>
                            </div>
                            <div class="space-y-1">
                                @foreach($times as $time)
                                <p class="text-gray-700">{{ $time }}</p>
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list-check mr-2 text-[#0d5c2f]"></i>
                        Requirements
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach($service->requirements as $requirement)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#0d5c2f]/10 text-[#0d5c2f]">
                            <i class="fas fa-check mr-1"></i>
                            {{ $requirement }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Bookings</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $service->bookings()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">This Month</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $service->bookings()->whereMonth('created_at', now()->month)->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ $service->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            @if($service->bookings()->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($service->bookings()->latest()->take(5)->get() as $booking)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 