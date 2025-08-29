@extends('layouts.priest')

@section('title', 'Notifications')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Notifications</h1>
                <p class="text-gray-600 mt-1 text-sm">Manage your notifications and stay updated</p>
            </div>
            <div class="flex items-center space-x-2">
                <button id="delete-selected" class="hidden inline-flex items-center px-3 py-2 border border-red-200 text-xs font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none transition-colors" onclick="document.getElementById('delete-modal').classList.remove('hidden')">
                    <i class="fas fa-trash-alt mr-1.5"></i>
                    Delete Selected
                </button>
                <button id="mark-all-read" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 focus:outline-none transition-colors">
                    <i class="fas fa-check-double mr-1.5"></i>
                    Mark All as Read
                </button>
                <button id="refresh-notifications" class="inline-flex items-center px-3 py-2 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors">
                    <i class="fas fa-sync-alt mr-1.5"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="p-6">
        <div id="notifications-container">
            @if($notifications->count() > 0)
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        @php
                            $message = strtolower($notification->message ?? '');
                            $icon = 'fas fa-bell';
                            $iconBg = 'bg-blue-100';
                            $iconColor = 'text-blue-600';
                            if (str_contains($message, 'booking')) { $icon = 'fas fa-calendar-check'; $iconBg = 'bg-green-100'; $iconColor = 'text-green-600'; }
                            elseif (str_contains($message, 'payment')) { $icon = 'fas fa-credit-card'; $iconBg = 'bg-purple-100'; $iconColor = 'text-purple-600'; }
                            elseif (str_contains($message, 'approved') || str_contains($message, 'confirmed')) { $icon = 'fas fa-check-circle'; $iconBg = 'bg-green-100'; $iconColor = 'text-green-600'; }
                            elseif (str_contains($message, 'rejected') || str_contains($message, 'cancelled')) { $icon = 'fas fa-times-circle'; $iconBg = 'bg-red-100'; $iconColor = 'text-red-600'; }
                            elseif (str_contains($message, 'reminder')) { $icon = 'fas fa-clock'; $iconBg = 'bg-orange-100'; $iconColor = 'text-orange-600'; }
                        @endphp
                        <div class="notification-item rounded-lg p-4 transition-colors border border-gray-200 {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50/50 border-l-4 border-[#0d5c2f]' }}" data-notification-id="{{ $notification->id }}">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 {{ $iconBg }} rounded-full flex items-center justify-center">
                                        <i class="{{ $icon }} {{ $iconColor }} text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900 font-medium leading-5 mb-1">{{ $notification->title }}</p>
                                            <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                                            <div class="flex items-center space-x-2 mt-2">
                                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                                @if(!$notification->read_at)
                                                    <span class="inline-block w-2 h-2 bg-[#0d5c2f] rounded-full"></span>
                                                @endif
                                            </div>
                                            @if($notification->type === 'admin_staff')
                                                <div class="mt-2 text-xs text-gray-500">
                                                    <i class="fas fa-user-shield mr-1"></i> From: {{ $notification->createdBy->name ?? 'System' }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(!$notification->read_at)
                                                <button class="mark-read-btn inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-[#0d5c2f] bg-[#0d5c2f]/10 hover:bg-[#0d5c2f]/20 focus:outline-none transition-colors" data-id="{{ $notification->id }}">
                                                    Mark as Read
                                                </button>
                                            @endif
                                            <input type="checkbox" class="notification-checkbox h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded" value="{{ $notification->id }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bell text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-900 text-base font-semibold">No notifications found</p>
                    <p class="text-gray-500 text-sm mt-1">You're all caught up!</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div id="pagination-container" class="mt-6 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    @if($notifications->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $notifications->previousPageUrl() }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors" data-page="{{ $notifications->currentPage() - 1 }}">Previous</a>
                    @endif
                    
                    <span class="px-3 py-2 text-sm text-gray-700">
                        Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}
                    </span>
                    
                    @if($notifications->hasMorePages())
                        <a href="{{ $notifications->nextPageUrl() }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors" data-page="{{ $notifications->currentPage() + 1 }}">Next</a>
                    @else
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
                    @endif
                </div>
                
                <div class="text-sm text-gray-700">
                    Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} results
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Notifications</h3>
            <div class="mt-2 px-7">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete the selected notifications? This action cannot be undone.
                </p>
            </div>
            <div class="flex items-center justify-center space-x-3 mt-6">
                <button id="cancel-delete" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPage = 1;
let selectedNotifications = new Set();

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    updateDeleteButton();
});

function setupEventListeners() {
    // Mark all as read
    document.getElementById('mark-all-read').addEventListener('click', markAllAsRead);
    
    // Refresh notifications
    document.getElementById('refresh-notifications').addEventListener('click', function() {
        window.location.reload();
    });

    // Delete modal
    document.getElementById('cancel-delete').addEventListener('click', function() {
        document.getElementById('delete-modal').classList.add('hidden');
    });

    document.getElementById('confirm-delete').addEventListener('click', deleteSelectedNotifications);
    
    // Setup notification event listeners
    setupNotificationEventListeners();
}

function setupNotificationEventListeners() {
    // Checkbox change events
    document.querySelectorAll('.notification-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const notificationId = this.value;
            if (this.checked) {
                selectedNotifications.add(notificationId);
            } else {
                selectedNotifications.delete(notificationId);
            }
            updateDeleteButton();
        });
    });

    // Mark as read buttons
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            markAsRead(notificationId);
        });
    });

    // Pagination
    document.querySelectorAll('.pagination-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.dataset.page;
            if (page) {
                currentPage = parseInt(page);
                // For now, just follow the link
                window.location.href = this.href;
            }
        });
    });
}

function markAsRead(notificationId) {
    fetch('{{ route("priest.notifications.mark-as-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ notification_ids: [notificationId] })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the notification item
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('bg-blue-50/50', 'border-[#0d5c2f]');
                notificationItem.classList.add('bg-gray-50', 'border-gray-200');
                
                const markReadBtn = notificationItem.querySelector('.mark-read-btn');
                if (markReadBtn) markReadBtn.remove();
            }
            
            // Update notification count in header
            updateHeaderNotificationCount();
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

function markAllAsRead() {
            fetch('{{ route("priest.notifications.mark-all-as-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to show updated state
            window.location.reload();
        }
    })
    .catch(error => console.error('Error marking all notifications as read:', error));
}

function updateDeleteButton() {
    const deleteButton = document.getElementById('delete-selected');
    if (deleteButton) {
        if (selectedNotifications.size > 0) {
            deleteButton.classList.remove('hidden');
            deleteButton.textContent = `Delete Selected (${selectedNotifications.size})`;
        } else {
            deleteButton.classList.add('hidden');
            deleteButton.textContent = 'Delete Selected';
        }
    }
}

function deleteSelectedNotifications() {
    if (selectedNotifications.size === 0) return;

    fetch('{{ route("priest.notifications.delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            notification_ids: Array.from(selectedNotifications)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide modal
            document.getElementById('delete-modal').classList.add('hidden');
            
            // Clear selection
            selectedNotifications.clear();
            updateDeleteButton();
            
            // Reload page to show updated notifications
            window.location.reload();
        }
    })
    .catch(error => console.error('Error deleting notifications:', error));
}

function updateHeaderNotificationCount() {
    if (typeof updateNotificationCount === 'function') {
        updateNotificationCount();
    }
}
</script>
@endpush 