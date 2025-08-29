@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Parochial Activities')

@section('content')
@include('components.toast')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-4 py-4 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">Parochial Activities</h1>
                    <p class="text-white/80 mt-1 text-xs">Manage parish events and activities</p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.create') : route('admin.parochial-activities.create') }}" 
                   class="group px-3 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-xs">
                    <i class="fas fa-plus mr-1.5 text-xs group-hover:scale-110 transition-transform duration-200"></i>
                    <span>Add Activity</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-calendar text-blue-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Total Activities</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['total_activities'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-check-circle text-green-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Active</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['active_activities'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-orange-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-clock text-orange-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Upcoming</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['upcoming_activities'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center mr-2">
                    <i class="fas fa-history text-gray-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Past</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['past_activities'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Activities -->
    @if($upcomingActivities->count() > 0)
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                <i class="fas fa-calendar-day mr-2 text-[#0d5c2f] text-sm"></i>
                Upcoming Activities (Next 7 Days)
            </h2>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($upcomingActivities as $activity)
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $activity->title }}</h3>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                            {!! $activity->status_badge !!}
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-calendar-alt mr-1.5 text-[#0d5c2f] text-xs"></i>
                            {{ $activity->formatted_datetime }}
                        </div>
                        @if($activity->location)
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-map-marker-alt mr-1.5 text-[#0d5c2f] text-xs"></i>
                            {{ $activity->location }}
                        </div>
                        @endif
                        <div class="flex items-center justify-between pt-2 border-t border-yellow-200">
                            <span class="text-xs px-2 py-0.5 bg-yellow-200 text-yellow-800 rounded-full font-medium">
                                {{ $activity->block_type_label }}
                            </span>
                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.show', $activity) : route('admin.parochial-activities.show', $activity) }}" 
                               class="text-[#0d5c2f] hover:text-[#0a4a26] font-medium text-xs flex items-center">
                                View Details <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- All Activities Table -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list mr-2 text-[#0d5c2f] text-sm"></i>
                All Activities
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Block Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-2 h-2 rounded-full mr-2 bg-yellow-400"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                    @if($activity->description)
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($activity->description, 50) }}</div>
                                    @endif
                                    @if($activity->is_recurring)
                                        <div class="text-xs text-yellow-600 font-medium flex items-center mt-1">
                                            <i class="fas fa-sync-alt mr-1 text-xs"></i>Recurring
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900">{{ $activity->formatted_date }}</div>
                            <div class="text-xs text-gray-500">{{ $activity->formatted_time }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ $activity->location ?? 'Not specified' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full font-medium">
                                {{ $activity->block_type_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            {!! $activity->status_badge !!}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-1">
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.show', $activity) : route('admin.parochial-activities.show', $activity) }}" 
                                   class="w-6 h-6 rounded-md bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors hover:scale-110" title="View">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.edit', $activity) : route('admin.parochial-activities.edit', $activity) }}" 
                                   class="w-6 h-6 rounded-md bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 transition-colors hover:scale-110" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <button onclick="openDeleteModal({{ $activity->id }}, '{{ $activity->title }}')" 
                                        class="w-6 h-6 rounded-md bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 transition-colors hover:scale-110" title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center">
                            <div class="text-gray-500">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-sm">
                                    <i class="fas fa-calendar-times text-white text-xl"></i>
                                </div>
                                <p class="text-base font-medium mb-2">No activities found</p>
                                <p class="text-sm mb-3">Get started by creating your first parochial activity</p>
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.create') : route('admin.parochial-activities.create') }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-[#0d5c2f] text-white rounded-md hover:bg-[#0a4a26] transition-colors text-xs">
                                    <i class="fas fa-plus mr-1.5 text-xs"></i>Create Activity
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($activities->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
            <div class="p-4">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Delete Activity</h3>
                        <p class="text-xs text-gray-600">This action cannot be undone</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-700">Are you sure you want to delete the activity "<span id="activityTitle" class="font-medium"></span>"? This will permanently remove the activity and all associated data.</p>
                </div>
                
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex items-center justify-end space-x-2">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-3 py-1.5 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors text-xs">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors font-medium text-xs">
                            <i class="fas fa-trash mr-1.5 text-xs"></i>Delete Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(activityId, activityTitle) {
    document.getElementById('activityTitle').textContent = activityTitle;
    const isStaff = {{ isset($isStaff) && $isStaff ? 'true' : 'false' }};
    const baseUrl = isStaff ? '/staff/parochial-activities' : '/admin/parochial-activities';
    document.getElementById('deleteForm').action = `${baseUrl}/${activityId}`;
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