@extends('layouts.ministry')

@section('title', $activity->title . ' - ' . $ministry->name)
@section('content')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('ministry.activities.index') }}" class="text-white/80 hover:text-white transition-colors mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ $activity->title }}
                        </h1>
                    </div>
                    <p class="text-white/80 text-sm">{{ $ministry->name }} Activity Details</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ministry.activities.edit', $activity) }}" 
                       class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2 text-sm"></i>
                        <span>Edit Activity</span>
                    </a>
                    <a href="{{ route('ministry.activities.index') }}" 
                       class="group px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-calendar mr-2 text-sm"></i>
                        <span>Back to Activities</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-[#0d5c2f] flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Activity Information</h3>
                            <p class="text-sm text-gray-500">Basic details about the activity</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $activity->title }}</p>
                        </div>

                        <!-- Description -->
                        @if($activity->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $activity->description }}</p>
                        </div>
                        @endif

                        <!-- Schedule -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date & Time</label>
                                <p class="text-gray-900">
                                    {{ $activity->start_at->format('F j, Y') }}
                                    @if(!$activity->is_all_day)
                                        at {{ $activity->start_at->format('g:i A') }}
                                    @endif
                                </p>
                            </div>
                            @if($activity->end_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date & Time</label>
                                <p class="text-gray-900">
                                    {{ $activity->end_at->format('F j, Y') }}
                                    @if(!$activity->is_all_day)
                                        at {{ $activity->end_at->format('g:i A') }}
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- All Day Indicator -->
                        @if($activity->is_all_day)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-center">
                                <i class="fas fa-sun text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-blue-900">All Day Event</span>
                            </div>
                        </div>
                        @endif

                        <!-- Location -->
                        @if($activity->location)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <p class="text-gray-900">{{ $activity->location }}</p>
                        </div>
                        @endif

                        <!-- Public/Private Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                            @if($activity->is_public)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-globe mr-1"></i>Public Activity
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    <i class="fas fa-lock mr-1"></i>Internal Activity
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Information Card -->
            @if($activity->estimated_budget)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <i class="fas fa-coins text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Budget Information</h3>
                            <p class="text-sm text-gray-500">Financial details and status</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Estimated Budget -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Budget</label>
                            <p class="text-2xl font-bold text-blue-600">₱{{ number_format($activity->estimated_budget, 2) }}</p>
                        </div>

                        <!-- Budget Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Budget Status</label>
                            @php
                                $statusColor = match($activity->budget_status_color) {
                                    'green' => 'bg-green-100 text-green-800',
                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                    'blue' => 'bg-blue-100 text-blue-800',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                                <i class="fas fa-circle mr-2 text-xs"></i>{{ $activity->budget_status_text }}
                            </span>
                        </div>

                        <!-- Budget Breakdown -->
                        @if($activity->budget_breakdown)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Budget Breakdown</label>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $activity->budget_breakdown }}</p>
                        </div>
                        @endif

                        <!-- Budget Request Actions -->
                        @if($activity->estimated_budget && !$activity->pendingBudgetRequest && !$activity->approvedBudgetRequest)
                        <div class="pt-4">
                            <form action="{{ route('ministry.activities.request-budget', $activity) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-coins mr-2"></i>Request Budget Approval
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('ministry.activities.edit', $activity) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit Activity
                        </a>
                        <button type="button" 
                                onclick="openDeleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>Delete Activity
                        </button>
                        <a href="{{ route('ministry.activities.index') }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Activity Stats Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="text-sm font-medium text-gray-900">{{ $activity->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $activity->updated_at->format('M j, Y') }}</span>
                        </div>
                        @if($activity->estimated_budget)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Budget Status</span>
                            <span class="text-sm font-medium text-gray-900">{{ $activity->budget_status_text }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Activity</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete "<span id="deleteActivityTitle" class="font-medium text-gray-900"></span>"?
                </p>
                <p class="text-xs text-red-600 mt-2">
                    This action cannot be undone. All associated budget requests and files will also be deleted.
                </p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button id="cancelDelete" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Delete Activity
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection 

<script>
    // Delete Modal Functions
    function openDeleteModal(activityId, activityTitle) {
        const modal = document.getElementById('deleteModal');
        const titleSpan = document.getElementById('deleteActivityTitle');
        const form = document.getElementById('deleteForm');
        
        // Set the activity title and form action
        titleSpan.textContent = activityTitle;
        form.action = `/ministry/activities/${activityId}`;
        
        // Show the modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Add backdrop click to close
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Close modal when cancel button is clicked
    document.addEventListener('DOMContentLoaded', function() {
        const cancelBtn = document.getElementById('cancelDelete');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeDeleteModal);
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    });
</script> 