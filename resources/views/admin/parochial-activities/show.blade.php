@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'View Parochial Activity')

@section('content')
@include('components.toast')
<div class="space-y-5">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-5 py-5 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $parochialActivity->title }}</h1>
                    <p class="text-white/80 mt-1.5 text-sm flex items-center">
                        <i class="fas fa-church mr-2 text-sm"></i>Activity Details
                    </p>
                </div>
                <div class="flex items-center space-x-2.5">
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.edit', $parochialActivity) : route('admin.parochial-activities.edit', $parochialActivity) }}" 
                       class="group px-3.5 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-sm">
                        <i class="fas fa-edit mr-1.5 text-sm group-hover:scale-110 transition-transform duration-200"></i>
                        <span>Edit</span>
                    </a>
                    @endif
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.index') : route('admin.parochial-activities.index') }}" 
                       class="group px-3.5 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-sm">
                        <i class="fas fa-arrow-left mr-1.5 text-sm group-hover:-translate-x-1 transition-transform duration-200"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2.5 text-[#0d5c2f] text-base"></i>
                        Activity Information
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-tag mr-2 text-[#0d5c2f] text-sm"></i>Title
                            </label>
                            <p class="text-gray-900 font-semibold text-sm">{{ $parochialActivity->title }}</p>
                        </div>
                        
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-[#0d5c2f] text-sm"></i>Status
                            </label>
                            <div class="mt-1">{!! $parochialActivity->status_badge !!}</div>
                        </div>

                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f] text-sm"></i>
                                {{ $parochialActivity->is_recurring ? 'Day of Week' : 'Event Date' }}
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->formatted_date }}</p>
                            @if($parochialActivity->is_recurring)
                                <p class="text-xs text-yellow-600 font-medium flex items-center mt-1.5">
                                    <i class="fas fa-sync-alt mr-1.5 text-xs"></i>Recurring Activity
                                </p>
                            @endif
                        </div>

                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-clock mr-2 text-[#0d5c2f] text-sm"></i>Time
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->formatted_time }}</p>
                        </div>

                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-ban mr-2 text-[#0d5c2f] text-sm"></i>Block Type
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->block_type_label }}</p>
                        </div>

                        @if($parochialActivity->location)
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-[#0d5c2f] text-sm"></i>Location
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->location }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->organizer)
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-user mr-2 text-[#0d5c2f] text-sm"></i>Organizer
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->organizer }}</p>
                        </div>
                        @endif
                    </div>

                    @if($parochialActivity->description)
                    <div class="mt-5 group bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2.5 flex items-center">
                            <i class="fas fa-align-left mr-2 text-[#0d5c2f] text-sm"></i>Description
                        </label>
                        <p class="text-gray-900 text-sm leading-relaxed">{{ $parochialActivity->description }}</p>
                    </div>
                    @endif

                    @if($parochialActivity->notes)
                    <div class="mt-5 group bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2.5 flex items-center">
                            <i class="fas fa-sticky-note mr-2 text-[#0d5c2f] text-sm"></i>Additional Notes
                        </label>
                        <p class="text-gray-900 text-sm leading-relaxed">{{ $parochialActivity->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            @if($parochialActivity->contact_person || $parochialActivity->contact_phone || $parochialActivity->contact_email)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-address-book mr-2.5 text-[#0d5c2f] text-base"></i>
                        Contact Information
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        @if($parochialActivity->contact_person)
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-user-tie mr-2 text-[#0d5c2f] text-sm"></i>Contact Person
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->contact_person }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->contact_phone)
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-phone mr-2 text-[#0d5c2f] text-sm"></i>Contact Phone
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->contact_phone }}</p>
                        </div>
                        @endif

                        @if($parochialActivity->contact_email)
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-envelope mr-2 text-[#0d5c2f] text-sm"></i>Contact Email
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->contact_email }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Recurring Information -->
            @if($parochialActivity->is_recurring)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-sync-alt mr-2.5 text-[#0d5c2f] text-base"></i>
                        Recurring Information
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-calendar-week mr-2 text-[#0d5c2f] text-sm"></i>Pattern
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ ucfirst($parochialActivity->recurring_pattern['type'] ?? 'weekly') }}</p>
                        </div>

                        @if($parochialActivity->recurring_end_date)
                        <div class="group bg-gray-50 rounded-lg p-3.5 hover:bg-gray-100 transition-colors duration-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center">
                                <i class="fas fa-calendar-times mr-2 text-[#0d5c2f] text-sm"></i>End Date
                            </label>
                            <p class="text-gray-900 text-sm font-medium">{{ $parochialActivity->recurring_end_date->format('F j, Y') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($affectedDates && count($affectedDates) > 0)
                    <div class="mt-5">
                        <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-[#0d5c2f] text-sm"></i>Upcoming Occurrences
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach(array_slice($affectedDates, 0, 12) as $date)
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                                <div class="text-sm font-semibold text-yellow-800">{{ $date->format('M j') }}</div>
                                <div class="text-xs text-yellow-600 flex items-center justify-center mt-1">
                                    <i class="fas fa-calendar-day mr-1 text-xs"></i>{{ $date->format('l') }}
                                </div>
                            </div>
                            @endforeach
                            @if(count($affectedDates) > 12)
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-3 text-center">
                                <div class="text-sm font-medium text-gray-600 flex items-center justify-center">
                                    <i class="fas fa-plus mr-1 text-sm"></i>{{ count($affectedDates) - 12 }} more
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
        <div class="space-y-5">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2.5 text-[#0d5c2f] text-base"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.edit', $parochialActivity) : route('admin.parochial-activities.edit', $parochialActivity) }}" 
                           class="w-full flex items-center justify-center px-3.5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm group shadow-sm hover:shadow-md">
                            <i class="fas fa-edit mr-2 text-sm group-hover:scale-110 transition-transform duration-200"></i>Edit Activity
                        </a>
                        
                        <button onclick="openDeleteModal()" 
                                class="w-full flex items-center justify-center px-3.5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 text-sm group shadow-sm hover:shadow-md">
                            <i class="fas fa-trash mr-2 text-sm group-hover:scale-110 transition-transform duration-200"></i>Delete Activity
                        </button>
                    </div>
                </div>
            </div>

            <!-- Activity Stats -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-2.5 text-[#0d5c2f] text-base"></i>
                        Activity Details
                    </h3>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-calendar-plus mr-2 text-[#0d5c2f] text-sm"></i>Created
                            </span>
                            <span class="text-sm font-medium text-gray-900">{{ $parochialActivity->created_at->format('M j, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-edit mr-2 text-[#0d5c2f] text-sm"></i>Last Updated
                            </span>
                            <span class="text-sm font-medium text-gray-900">{{ $parochialActivity->updated_at->format('M j, Y') }}</span>
                        </div>

                        @if($parochialActivity->is_recurring)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-sync-alt mr-2 text-yellow-600 text-sm"></i>Type
                            </span>
                            <span class="text-sm font-medium text-yellow-600">Recurring</span>
                        </div>
                        @else
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-calendar-day mr-2 text-[#0d5c2f] text-sm"></i>Type
                            </span>
                            <span class="text-sm font-medium text-gray-900">One-time</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-palette mr-2 text-[#0d5c2f] text-sm"></i>Calendar Color
                            </span>
                            <div class="flex items-center">
                                <div class="w-3.5 h-3.5 rounded-full bg-yellow-400 mr-2"></div>
                                <span class="text-sm font-medium text-gray-900">Yellow</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
            <div class="p-5">
                <div class="flex items-center mb-5">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600 text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Delete Activity</h3>
                        <p class="text-sm text-gray-600">This action cannot be undone</p>
                    </div>
                </div>
                
                <div class="mb-5">
                    <p class="text-sm text-gray-700">Are you sure you want to delete the activity "<span class="font-semibold">{{ $parochialActivity->title }}</span>"? This will permanently remove the activity and all associated data.</p>
                </div>
                
                <form action="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.destroy', $parochialActivity) : route('admin.parochial-activities.destroy', $parochialActivity) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex items-center justify-end space-x-2.5">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-3.5 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium text-sm">
                            <i class="fas fa-trash mr-2 text-sm"></i>Delete Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection 