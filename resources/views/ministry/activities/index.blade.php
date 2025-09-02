@extends('layouts.ministry')

@section('title', 'Activities - ' . $ministry->name)
@section('content')
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('ministry.dashboard') }}" class="text-white/80 hover:text-white transition-colors mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ $ministry->name }} Activities
                        </h1>
                    </div>
                    <p class="text-white/80 text-sm">Manage your ministry activities and budget planning</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ministry.activities.create') }}" 
                       class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-plus mr-2 text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                        <span>Add Activity</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle (Full width tab style) -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="flex border-b border-gray-200">
            <button id="table-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-table mr-2"></i> Table View
            </button>
            <button id="card-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-th-large mr-2"></i> Cards View
            </button>
        </div>
        
        <div class="p-4">
            @if($activities->count() > 0)
                <!-- Table View -->
                <div id="table-view" class="overflow-x-auto hidden animate-fadeIn">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 animate-slideInUp" style="animation-delay: {{ $loop->index * 50 }}ms">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-[#0d5c2f] flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                            <i class="fas fa-calendar text-white text-sm"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($activity->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $activity->start_at->format("M j, Y") }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if($activity->is_all_day)
                                            All Day
                                        @else
                                            {{ $activity->start_at->format("g:i A") }}
                                            @if($activity->end_at)
                                                - {{ $activity->end_at->format("g:i A") }}
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $activity->location ?: "—" }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($activity->estimated_budget)
                                        <div class="text-sm font-medium text-gray-900">₱{{ number_format($activity->estimated_budget, 2) }}</div>
                                        <div class="text-xs text-gray-500">{{ $activity->budget_status_text }}</div>
                                    @else
                                        <span class="text-sm text-gray-500">No budget</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($activity->is_public)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-globe mr-1"></i>Public
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-lock mr-1"></i>Internal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($activity->estimated_budget && !$activity->pendingBudgetRequest && !$activity->approvedBudgetRequest)
                                            <form action="{{ route('ministry.activities.request-budget', $activity) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" title="Request Budget">
                                                    <i class="fas fa-coins text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('ministry.activities.show', $activity) }}" class="w-7 h-7 rounded-lg bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-all duration-200 hover:scale-110" title="View">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('ministry.activities.edit', $activity) }}" class="w-7 h-7 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-all duration-200 hover:scale-110" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <button type="button" 
                                                onclick="openDeleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                                class="w-7 h-7 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110" 
                                                title="Delete">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cards View -->
                <div id="card-view" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 hidden animate-fadeIn">
                    @foreach($activities as $activity)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 animate-slideInUp hover:-translate-y-1" style="animation-delay: {{ $loop->index * 100 }}ms">
                        <div class="p-4">
                            <div class="flex items-center mb-3">
                                <div class="h-10 w-10 rounded-full bg-[#0d5c2f] flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                    <i class="fas fa-calendar text-white text-sm"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-base font-medium text-gray-900">{{ $activity->title }}</h3>
                                    <p class="text-xs text-gray-500">{{ Str::limit($activity->description, 60) }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Date:</span>
                                    <span class="text-xs text-gray-900">{{ $activity->start_at->format("M j, Y") }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Time:</span>
                                    <span class="text-xs text-gray-900">
                                        @if($activity->is_all_day)
                                            All Day
                                        @else
                                            {{ $activity->start_at->format("g:i A") }}
                                            @if($activity->end_at)
                                                - {{ $activity->end_at->format("g:i A") }}
                                            @endif
                                        @endif
                                    </span>
                                </div>
                                
                                @if($activity->location)
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Location:</span>
                                    <span class="text-xs text-gray-900">{{ $activity->location }}</span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Budget:</span>
                                    @if($activity->estimated_budget)
                                        <span class="text-xs font-medium text-gray-900">₱{{ number_format($activity->estimated_budget, 2) }}</span>
                                    @else
                                        <span class="text-xs text-gray-500">No budget</span>
                                    @endif
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Status:</span>
                                    @if($activity->is_public)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-globe mr-1"></i>Public
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 transition-all duration-200 hover:scale-105">
                                            <i class="fas fa-lock mr-1"></i>Internal
                                        </span>
                                    @endif
                                </div>

                                @if($activity->estimated_budget)
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Budget Status:</span>
                                    @php
                                        $statusColor = match($activity->budget_status_color) {
                                            'green' => 'bg-green-100 text-green-800',
                                            'yellow' => 'bg-yellow-100 text-yellow-800',
                                            'blue' => 'bg-blue-100 text-blue-800',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }} transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-circle mr-1 text-xs"></i>{{ $activity->budget_status_text }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-2">
                            @if($activity->estimated_budget && !$activity->pendingBudgetRequest && !$activity->approvedBudgetRequest)
                                <form action="{{ route('ministry.activities.request-budget', $activity) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all duration-200 hover:scale-110" title="Request Budget">
                                        <i class="fas fa-coins text-xs"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('ministry.activities.show', $activity) }}" class="w-7 h-7 rounded-lg bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 hover:text-green-800 transition-all duration-200 hover:scale-110" title="View">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('ministry.activities.edit', $activity) }}" class="w-7 h-7 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 hover:text-indigo-800 transition-all duration-200 hover:scale-110" title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <button type="button" 
                                    onclick="openDeleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                    class="w-7 h-7 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 hover:text-red-800 transition-all duration-200 hover:scale-110" 
                                    title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $activities->links() }}
                </div>
            @else
                <div class="text-center py-8 animate-fadeIn">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-sm">
                        <i class="fas fa-calendar text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No activities found</h3>
                    <p class="text-gray-600 text-sm">No activities have been created for this ministry yet.</p>
                    <div class="mt-4">
                        <a href="{{ route('ministry.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Create Your First Activity
                        </a>
                    </div>
                </div>
            @endif
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

    document.addEventListener("DOMContentLoaded", function() {
        const tableViewBtn = document.getElementById("table-view-btn");
        const cardViewBtn = document.getElementById("card-view-btn");
        const tableView = document.getElementById("table-view");
        const cardView = document.getElementById("card-view");
        
        // Function to toggle views with animation
        function showTableView() {
            if (window.innerWidth >= 768) { // md breakpoint
                cardView.style.opacity = "0";
                cardView.style.transform = "translateY(10px)";
                
                setTimeout(() => {
                    cardView.classList.add("hidden");
                    tableView.classList.remove("hidden");
                    
                    // Trigger animation
                    tableView.style.opacity = "0";
                    tableView.style.transform = "translateY(10px)";
                    
                    requestAnimationFrame(() => {
                        tableView.style.transition = "all 0.3s ease-out";
                        tableView.style.opacity = "1";
                        tableView.style.transform = "translateY(0)";
                    });
                }, 150);
                
                tableViewBtn.classList.add("text-[#0d5c2f]", "border-[#0d5c2f]");
                tableViewBtn.classList.remove("text-gray-600", "border-transparent");
                cardViewBtn.classList.remove("text-[#0d5c2f]", "border-[#0d5c2f]");
                cardViewBtn.classList.add("text-gray-600", "border-transparent");
                
                // Save preference
                localStorage.setItem("ministryActivityViewPreference", "table");
            }
        }
        
        function showCardView() {
            if (window.innerWidth >= 768) { // Only allow card view on desktop
                tableView.style.opacity = "0";
                tableView.style.transform = "translateY(10px)";
                
                setTimeout(() => {
                    tableView.classList.add("hidden");
                    cardView.classList.remove("hidden");
                    
                    // Trigger animation
                    cardView.style.opacity = "0";
                    cardView.style.transform = "translateY(10px)";
                    
                    requestAnimationFrame(() => {
                        cardView.style.transition = "all 0.3s ease-out";
                        cardView.style.opacity = "1";
                        cardView.style.transform = "translateY(0)";
                    });
                }, 150);
                
                cardViewBtn.classList.add("text-[#0d5c2f]", "border-[#0d5c2f]");
                cardViewBtn.classList.remove("text-gray-600", "border-transparent");
                tableViewBtn.classList.remove("text-[#0d5c2f]", "border-[#0d5c2f]");
                tableViewBtn.classList.add("text-gray-600", "border-transparent");
                
                // Save preference
                localStorage.setItem("ministryActivityViewPreference", "card");
            }
        }
        
        // Event listeners
        if (tableViewBtn) {
            tableViewBtn.addEventListener("click", showTableView);
        }
        if (cardViewBtn) {
            cardViewBtn.addEventListener("click", showCardView);
        }
        
        // Check for saved preference
        const savedPreference = localStorage.getItem("ministryActivityViewPreference");
        
        // Initial view setup
        if (window.innerWidth < 768) {
            // Always show cards on mobile
            if (cardView) cardView.classList.remove("hidden");
            if (tableView) tableView.classList.add("hidden");
        } else {
            // On desktop, respect user preference if available
            if (savedPreference === "card") {
                showCardView();
            } else {
                // Default to table view
                showTableView();
            }
        }
        
        // Handle window resize
        window.addEventListener("resize", function() {
            if (window.innerWidth < 768) {
                // Force card view on mobile
                if (cardView) cardView.classList.remove("hidden");
                if (tableView) tableView.classList.add("hidden");
            } else {
                // On desktop, restore the saved preference
                const currentPreference = localStorage.getItem("ministryActivityViewPreference");
                if (currentPreference === "card") {
                    showCardView();
                } else {
                    showTableView();
                }
            }
        });
    });
</script>
@endsection


