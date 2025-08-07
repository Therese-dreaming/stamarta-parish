@extends('layouts.admin')

@section('title', 'View Parochial Activity')

@section('content')
@include('components.toast')
<div class="font-[Poppins]">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $parochialActivity->title }}</h1>
                <p class="text-gray-600">Activity Details</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.parochial-activities.edit', $parochialActivity) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('admin.parochial-activities.index') }}" class="text-[#0d5c2f] hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Activities
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Activity Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <p class="text-gray-900 font-medium">{{ $parochialActivity->title }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="mt-1">{!! $parochialActivity->status_badge !!}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $parochialActivity->is_recurring ? 'Day of Week' : 'Event Date' }}
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->formatted_date }}</p>
                            @if($parochialActivity->is_recurring)
                                <p class="text-sm text-yellow-600 font-medium">🔄 Recurring Activity</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                            <p class="text-gray-900">{{ $parochialActivity->formatted_time }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Block Type</label>
                            <p class="text-gray-900">{{ $parochialActivity->block_type_label }}</p>
                        </div>

                        @if($parochialActivity->location)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <p class="text-gray-900">{{ $parochialActivity->location }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->organizer)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organizer</label>
                            <p class="text-gray-900">{{ $parochialActivity->organizer }}</p>
                        </div>
                        @endif
                    </div>

                    @if($parochialActivity->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <p class="text-gray-900">{{ $parochialActivity->description }}</p>
                    </div>
                    @endif

                    @if($parochialActivity->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                        <p class="text-gray-900">{{ $parochialActivity->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            @if($parochialActivity->contact_person || $parochialActivity->contact_phone || $parochialActivity->contact_email)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Contact Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if($parochialActivity->contact_person)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <p class="text-gray-900">{{ $parochialActivity->contact_person }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->contact_phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <p class="text-gray-900">{{ $parochialActivity->contact_phone }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->contact_email)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                            <p class="text-gray-900">{{ $parochialActivity->contact_email }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Recurring Information -->
            @if($parochialActivity->is_recurring)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recurring Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pattern</label>
                            <p class="text-gray-900">{{ ucfirst($parochialActivity->recurring_pattern['type'] ?? 'weekly') }}</p>
                        </div>

                        @if($parochialActivity->recurring_end_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <p class="text-gray-900">{{ $parochialActivity->recurring_end_date->format('F j, Y') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($affectedDates && count($affectedDates) > 0)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upcoming Occurrences</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                            @foreach(array_slice($affectedDates, 0, 12) as $date)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-2 text-center">
                                <div class="text-sm font-medium text-yellow-800">{{ $date->format('M j') }}</div>
                                <div class="text-xs text-yellow-600">{{ $date->format('l') }}</div>
                            </div>
                            @endforeach
                            @if(count($affectedDates) > 12)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-2 text-center">
                                <div class="text-sm font-medium text-gray-600">+{{ count($affectedDates) - 12 }} more</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.parochial-activities.edit', $parochialActivity) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Activity
                        </a>
                        
                        <form action="{{ route('admin.parochial-activities.destroy', $parochialActivity) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this activity?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Activity
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Activity Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Activity Details</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="text-sm font-medium text-gray-900">{{ $parochialActivity->created_at->format('M j, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $parochialActivity->updated_at->format('M j, Y') }}</span>
                        </div>

                        @if($parochialActivity->is_recurring)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Type</span>
                            <span class="text-sm font-medium text-yellow-600">🔄 Recurring</span>
                        </div>
                        @else
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Type</span>
                            <span class="text-sm font-medium text-gray-900">One-time</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Calendar Color</span>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-yellow-400 mr-2"></div>
                                <span class="text-sm font-medium text-gray-900">Yellow</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 