@extends('layouts.admin')

@section('title', 'Service Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Service Management</h1>
            <p class="text-gray-600 mt-1">Manage parish services and schedules</p>
        </div>
    </div>

    <!-- Services List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($services as $service)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Service Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h3>
                    @if($service->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>Inactive
                        </span>
                    @endif
                </div>
                <p class="text-gray-600 text-sm">{{ $service->description }}</p>
            </div>

            <!-- Service Details -->
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Duration</label>
                        <p class="text-gray-900">{{ $service->formatted_duration }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Max Slots</label>
                        <p class="text-gray-900">{{ $service->max_slots }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Fees</label>
                    <p class="text-gray-900 text-sm">{{ $service->formatted_fees }}</p>
                </div>

                <!-- Schedule Preview -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Schedule</label>
                    @if($service->schedules && count($service->schedules) > 0)
                        <div class="space-y-1">
                            @foreach($service->schedules as $day => $times)
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600 capitalize">{{ $day }}</span>
                                    <span class="text-gray-900">{{ implode(', ', $times) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-500 italic">No schedule set</p>
                    @endif
                </div>

                <!-- Requirements Preview -->
                @if($service->requirements && count($service->requirements) > 0)
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Requirements</label>
                    <div class="flex flex-wrap gap-1">
                        @foreach(array_slice($service->requirements, 0, 3) as $requirement)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#0d5c2f]/10 text-[#0d5c2f]">
                                {{ $requirement }}
                            </span>
                        @endforeach
                        @if(count($service->requirements) > 3)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                +{{ count($service->requirements) - 3 }} more
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.services.edit', $service) }}" 
                       class="text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-{{ $service->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $service->is_active ? 'yellow' : 'green' }}-800 text-sm font-medium">
                            <i class="fas fa-{{ $service->is_active ? 'pause' : 'play' }} mr-1"></i>
                            {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($services->count() === 0)
    <div class="text-center py-12">
        <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No services found</h3>
        <p class="text-gray-600">No services have been configured yet.</p>
    </div>
    @endif
</div>
@endsection 