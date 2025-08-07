@extends('layouts.admin')

@section('title', 'Parochial Activities')

@section('content')
@include('components.toast')
<div class="font-[Poppins]">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Parochial Activities</h1>
            <p class="text-gray-600">Manage parish events and activities</p>
        </div>
        <a href="{{ route('admin.parochial-activities.create') }}" class="bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#0a4a26] transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Activity
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Activities</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_activities'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_activities'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Upcoming</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['upcoming_activities'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-gray-100 rounded-lg">
                    <i class="fas fa-history text-gray-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Past</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['past_activities'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Activities -->
    @if($upcomingActivities->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Upcoming Activities (Next 7 Days)</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($upcomingActivities as $activity)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $activity->title }}</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                {!! $activity->status_badge !!}
                            </div>
                        </div>
                    <p class="text-sm text-gray-600 mb-2">{{ $activity->formatted_datetime }}</p>
                    @if($activity->location)
                        <p class="text-sm text-gray-600 mb-2"><i class="fas fa-map-marker-alt mr-1"></i>{{ $activity->location }}</p>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                            {{ $activity->block_type_label }}
                        </span>
                        <a href="{{ route('admin.parochial-activities.show', $activity) }}" class="text-[#0d5c2f] hover:underline text-sm">View Details</a>
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
            <h2 class="text-lg font-semibold text-gray-900">All Activities</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Block Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3 bg-yellow-400"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                    @if($activity->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($activity->description, 50) }}</div>
                                    @endif
                                    @if($activity->is_recurring)
                                        <div class="text-xs text-yellow-600 font-medium">🔄 Recurring</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $activity->formatted_date }}</div>
                            <div class="text-sm text-gray-500">{{ $activity->formatted_time }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $activity->location ?? 'Not specified' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                {{ $activity->block_type_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {!! $activity->status_badge !!}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.parochial-activities.show', $activity) }}" class="text-[#0d5c2f] hover:text-[#0a4a26]">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.parochial-activities.edit', $activity) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.parochial-activities.destroy', $activity) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this activity?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No activities found. <a href="{{ route('admin.parochial-activities.create') }}" class="text-[#0d5c2f] hover:underline">Create your first activity</a>
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
@endsection 