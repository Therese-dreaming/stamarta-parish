@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Parochial Activities')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-sm">
        <div class="px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Parochial Activities</h1>
                    <p class="text-white/80 mt-1">Manage parish events and activities</p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.create') : route('admin.parochial-activities.create') }}" 
                   class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Add Activity">
                    <i class="fas fa-plus text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Activities</p>
                    <p class="text-3xl font-bold">{{ $stats['total_activities'] }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Active</p>
                    <p class="text-3xl font-bold">{{ $stats['active_activities'] }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Upcoming</p>
                    <p class="text-3xl font-bold">{{ $stats['upcoming_activities'] }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-100 text-sm font-medium">Past</p>
                    <p class="text-3xl font-bold">{{ $stats['past_activities'] }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-history text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Activities -->
    @if($upcomingActivities->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-calendar-day mr-2 text-[#0d5c2f]"></i>
                Upcoming Activities (Next 7 Days)
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingActivities as $activity)
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="font-semibold text-gray-900 text-lg">{{ $activity->title }}</h3>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            {!! $activity->status_badge !!}
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f]"></i>
                            {{ $activity->formatted_datetime }}
                        </div>
                        @if($activity->location)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2 text-[#0d5c2f]"></i>
                            {{ $activity->location }}
                        </div>
                        @endif
                        <div class="flex items-center justify-between pt-3 border-t border-yellow-200">
                            <span class="text-xs px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full font-medium">
                                {{ $activity->block_type_label }}
                            </span>
                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.show', $activity) : route('admin.parochial-activities.show', $activity) }}" 
                               class="text-[#0d5c2f] hover:text-[#0a4a26] font-medium text-sm flex items-center">
                                View Details <i class="fas fa-arrow-right ml-1"></i>
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list mr-2 text-[#0d5c2f]"></i>
                All Activities
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Block Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3 bg-yellow-400"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                    @if($activity->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($activity->description, 50) }}</div>
                                    @endif
                                    @if($activity->is_recurring)
                                        <div class="text-xs text-yellow-600 font-medium flex items-center mt-1">
                                            <i class="fas fa-sync-alt mr-1"></i>Recurring
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $activity->formatted_date }}</div>
                            <div class="text-sm text-gray-500">{{ $activity->formatted_time }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $activity->location ?? 'Not specified' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-medium">
                                {{ $activity->block_type_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {!! $activity->status_badge !!}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.show', $activity) : route('admin.parochial-activities.show', $activity) }}" 
                                   class="w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors" title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.edit', $activity) : route('admin.parochial-activities.edit', $activity) }}" 
                                   class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button onclick="openDeleteModal({{ $activity->id }}, '{{ $activity->title }}')" 
                                        class="w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 transition-colors" title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium mb-2">No activities found</p>
                                <p class="text-sm mb-4">Get started by creating your first parochial activity</p>
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.parochial-activities.create') : route('admin.parochial-activities.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Create Activity
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Delete Activity</h3>
                        <p class="text-sm text-gray-600">This action cannot be undone</p>
                    </div>
                </div>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-700">Are you sure you want to delete the activity "<span id="activityTitle" class="font-medium"></span>"? This will permanently remove the activity and all associated data.</p>
                </div>
                
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            <i class="fas fa-trash mr-2"></i>Delete Activity
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