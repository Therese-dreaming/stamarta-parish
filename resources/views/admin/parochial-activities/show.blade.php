@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'View Parochial Activity')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $parochialActivity->title }}</h1>
                    <p class="text-white/80 mt-1 flex items-center">
                        <i class="fas fa-church mr-2"></i>Activity Details
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.edit', $parochialActivity) : route('admin.parochial-activities.edit', $parochialActivity) }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Edit Activity">
                        <i class="fas fa-edit text-lg"></i>
                    </a>
                    @endif
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.index') : route('admin.parochial-activities.index') }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Activities">
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
                        Activity Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-tag mr-2 text-[#0d5c2f]"></i>Title
                            </label>
                            <p class="text-gray-900 font-medium">{{ $parochialActivity->title }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-[#0d5c2f]"></i>Status
                            </label>
                            <div class="mt-1">{!! $parochialActivity->status_badge !!}</div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f]"></i>
                                {{ $parochialActivity->is_recurring ? 'Day of Week' : 'Event Date' }}
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->formatted_date }}</p>
                            @if($parochialActivity->is_recurring)
                                <p class="text-sm text-yellow-600 font-medium flex items-center mt-1">
                                    <i class="fas fa-sync-alt mr-1"></i>Recurring Activity
                                </p>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>Time
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->formatted_time }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-ban mr-2 text-[#0d5c2f]"></i>Block Type
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->block_type_label }}</p>
                        </div>

                        @if($parochialActivity->location)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-[#0d5c2f]"></i>Location
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->location }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->organizer)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>Organizer
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->organizer }}</p>
                        </div>
                        @endif
                    </div>

                    @if($parochialActivity->description)
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-align-left mr-2 text-[#0d5c2f]"></i>Description
                        </label>
                        <p class="text-gray-900">{{ $parochialActivity->description }}</p>
                    </div>
                    @endif

                    @if($parochialActivity->notes)
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-sticky-note mr-2 text-[#0d5c2f]"></i>Additional Notes
                        </label>
                        <p class="text-gray-900">{{ $parochialActivity->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            @if($parochialActivity->contact_person || $parochialActivity->contact_phone || $parochialActivity->contact_email)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-address-book mr-2 text-[#0d5c2f]"></i>
                        Contact Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if($parochialActivity->contact_person)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-user-tie mr-2 text-[#0d5c2f]"></i>Contact Person
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->contact_person }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->contact_phone)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-phone mr-2 text-[#0d5c2f]"></i>Contact Phone
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->contact_phone }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->contact_email)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-envelope mr-2 text-[#0d5c2f]"></i>Contact Email
                            </label>
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
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-sync-alt mr-2 text-[#0d5c2f]"></i>
                        Recurring Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-week mr-2 text-[#0d5c2f]"></i>Pattern
                            </label>
                            <p class="text-gray-900">{{ ucfirst($parochialActivity->recurring_pattern['type'] ?? 'weekly') }}</p>
                        </div>

                        @if($parochialActivity->recurring_end_date)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-times mr-2 text-[#0d5c2f]"></i>End Date
                            </label>
                            <p class="text-gray-900">{{ $parochialActivity->recurring_end_date->format('F j, Y') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($affectedDates && count($affectedDates) > 0)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-[#0d5c2f]"></i>Upcoming Occurrences
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach(array_slice($affectedDates, 0, 12) as $date)
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-3 text-center hover:shadow-md transition-shadow">
                                <div class="text-sm font-medium text-yellow-800">{{ $date->format('M j') }}</div>
                                <div class="text-xs text-yellow-600 flex items-center justify-center">
                                    <i class="fas fa-calendar-day mr-1"></i>{{ $date->format('l') }}
                                </div>
                            </div>
                            @endforeach
                            @if(count($affectedDates) > 12)
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-3 text-center">
                                <div class="text-sm font-medium text-gray-600 flex items-center justify-center">
                                    <i class="fas fa-plus mr-1"></i>{{ count($affectedDates) - 12 }} more
                                </div>
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
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-[#0d5c2f]"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.edit', $parochialActivity) : route('admin.parochial-activities.edit', $parochialActivity) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Activity
                        </a>
                        
                        <form action="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.destroy', $parochialActivity) : route('admin.parochial-activities.destroy', $parochialActivity) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this activity?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-red-700 text-white rounded-lg hover:bg-red-800 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Activity
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Activity Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-[#0d5c2f]"></i>
                        Activity Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-calendar-plus mr-2 text-[#0d5c2f]"></i>Created
                            </span>
                            <span class="text-sm font-medium text-gray-900">{{ $parochialActivity->created_at->format('M j, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-edit mr-2 text-[#0d5c2f]"></i>Last Updated
                            </span>
                            <span class="text-sm font-medium text-gray-900">{{ $parochialActivity->updated_at->format('M j, Y') }}</span>
                        </div>

                        @if($parochialActivity->is_recurring)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-sync-alt mr-2 text-yellow-600"></i>Type
                            </span>
                            <span class="text-sm font-medium text-yellow-600">Recurring</span>
                        </div>
                        @else
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-calendar-day mr-2 text-[#0d5c2f]"></i>Type
                            </span>
                            <span class="text-sm font-medium text-gray-900">One-time</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-palette mr-2 text-[#0d5c2f]"></i>Calendar Color
                            </span>
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