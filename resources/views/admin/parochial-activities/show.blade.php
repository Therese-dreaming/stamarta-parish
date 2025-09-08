@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'View Parochial Activity')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-8 relative">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/10 rounded-bl-full"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-tr-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $parochialActivity->title }}</h1>
                    <div class="flex items-center space-x-4 text-white/90">
                        <div class="flex items-center">
                            <i class="fas fa-church mr-2 text-lg"></i>
                            <span class="text-sm font-medium">Parochial Activity</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-lg"></i>
                            <span class="text-sm font-medium">{{ $parochialActivity->formatted_date }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-lg"></i>
                            <span class="text-sm font-medium">{{ $parochialActivity->formatted_time }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.edit', $parochialActivity) : route('admin.parochial-activities.edit', $parochialActivity) }}" 
                       class="group px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow-md text-sm font-medium">
                        <i class="fas fa-edit mr-2 text-sm group-hover:scale-110 transition-transform duration-200"></i>
                        <span>Edit Activity</span>
                    </a>
                    @endif
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.index') : route('admin.parochial-activities.index') }}" 
                       class="group px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2 text-sm group-hover:-translate-x-1 transition-transform duration-200"></i>
                        <span>Back to Activities</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-church text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Activity Overview</h2>
                                <p class="text-sm text-gray-500">Key information about this parochial activity</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            {!! $parochialActivity->status_badge !!}
                            @if($parochialActivity->is_recurring)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-sync-alt mr-1"></i>Recurring
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Event Date</p>
                                    <p class="text-sm font-semibold text-blue-900">{{ $parochialActivity->formatted_date }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Time</p>
                                    <p class="text-sm font-semibold text-green-900">{{ $parochialActivity->formatted_time }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-ban text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-purple-600 uppercase tracking-wide">Block Type</p>
                                    <p class="text-sm font-semibold text-purple-900">{{ $parochialActivity->block_type_label }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-[#0d5c2f] rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Activity Details</h2>
                            <p class="text-sm text-gray-500">Comprehensive information about this activity</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        @if($parochialActivity->location)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">Location</h3>
                                <p class="text-gray-700">{{ $parochialActivity->location }}</p>
                            </div>
                        </div>
                        @endif

                        @if($parochialActivity->organizer)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-tie text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">Organizer</h3>
                                <p class="text-gray-700">{{ $parochialActivity->organizer }}</p>
                            </div>
                        </div>
                        @endif

                        @if($parochialActivity->description)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-align-left text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">Description</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $parochialActivity->description }}</p>
                            </div>
                        </div>
                        @endif

                        @if($parochialActivity->notes)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-sticky-note text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">Additional Notes</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $parochialActivity->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            @if($parochialActivity->contact_person || $parochialActivity->contact_phone || $parochialActivity->contact_email)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-address-book text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Contact Information</h2>
                            <p class="text-sm text-gray-500">Get in touch with the activity organizer</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($parochialActivity->contact_person)
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 border border-indigo-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-tie text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-indigo-600 uppercase tracking-wide">Contact Person</p>
                                    <p class="text-sm font-semibold text-indigo-900">{{ $parochialActivity->contact_person }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($parochialActivity->contact_phone)
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Phone</p>
                                    <p class="text-sm font-semibold text-green-900">{{ $parochialActivity->contact_phone }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($parochialActivity->contact_email)
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Email</p>
                                    <p class="text-sm font-semibold text-blue-900">{{ $parochialActivity->contact_email }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Recurring Information -->
            @if($parochialActivity->is_recurring)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-sync-alt text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Recurring Schedule</h2>
                            <p class="text-sm text-gray-500">Information about the recurring pattern</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-week text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Pattern</p>
                                    <p class="text-sm font-semibold text-blue-900">{{ ucfirst($parochialActivity->recurring_pattern['type'] ?? 'weekly') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($parochialActivity->recurring_end_date)
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-times text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-red-600 uppercase tracking-wide">End Date</p>
                                    <p class="text-sm font-semibold text-red-900">{{ $parochialActivity->recurring_end_date->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($affectedDates && count($affectedDates) > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-[#0d5c2f]"></i>Upcoming Occurrences
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach(array_slice($affectedDates, 0, 12) as $date)
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-4 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-1 group">
                                <div class="text-lg font-bold text-yellow-800 group-hover:text-yellow-900">{{ $date->format('M j') }}</div>
                                <div class="text-xs text-yellow-600 flex items-center justify-center mt-1">
                                    <i class="fas fa-calendar-day mr-1 text-xs"></i>{{ $date->format('l') }}
                                </div>
                            </div>
                            @endforeach
                            @if(count($affectedDates) > 12)
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-all duration-200">
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
        <div class="space-y-6 sticky top-6 self-start">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-[#0d5c2f] rounded-xl flex items-center justify-center">
                            <i class="fas fa-bolt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                            <p class="text-sm text-gray-500">Manage this activity</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.edit', $parochialActivity) : route('admin.parochial-activities.edit', $parochialActivity) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 text-sm font-medium group shadow-sm hover:shadow-md transform hover:scale-105">
                            <i class="fas fa-edit mr-2 text-sm group-hover:scale-110 transition-transform duration-200"></i>Edit Activity
                        </a>
                        
                        <button onclick="openDeleteModal()" 
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200 text-sm font-medium group shadow-sm hover:shadow-md transform hover:scale-105">
                            <i class="fas fa-trash mr-2 text-sm group-hover:scale-110 transition-transform duration-200"></i>Delete Activity
                        </button>
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-gray-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Activity Info</h3>
                            <p class="text-sm text-gray-500">Key details and metadata</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-plus text-green-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Created</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $parochialActivity->created_at->format('M j, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-edit text-blue-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Last Updated</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $parochialActivity->updated_at->format('M j, Y') }}</span>
                        </div>

                        @if($parochialActivity->is_recurring)
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-sync-alt text-blue-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-blue-700">Type</span>
                            </div>
                            <span class="text-sm font-semibold text-blue-900">Recurring</span>
                        </div>
                        @else
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-day text-gray-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Type</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">One-time</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-palette text-yellow-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Calendar Color</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 rounded-full bg-yellow-400"></div>
                                <span class="text-sm font-semibold text-gray-900">Yellow</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="p-6">
            <div class="flex items-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Delete Activity</h3>
                    <p class="text-sm text-gray-600 mt-1">This action cannot be undone</p>
                </div>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-700 leading-relaxed">Are you sure you want to delete the activity <span class="font-semibold text-gray-900">"{{ $parochialActivity->title }}"</span>? This will permanently remove the activity and all associated data.</p>
            </div>
            
            <form action="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.destroy', $parochialActivity) : route('admin.parochial-activities.destroy', $parochialActivity) }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" 
                            onclick="closeDeleteModal()"
                            class="px-6 py-3 text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200 font-medium text-sm shadow-sm hover:shadow-md transform hover:scale-105">
                        <i class="fas fa-trash mr-2 text-sm"></i>Delete Activity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    
    // Trigger close animation
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('deleteModal');
        if (!modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    }
});
</script>
@endsection 