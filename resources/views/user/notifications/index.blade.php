@extends('layouts.user')

@section('title', 'Notifications')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                <p class="text-gray-600 mt-1">Manage your notifications and stay updated</p>
            </div>
            <div class="flex items-center space-x-3">
                <button id="mark-all-read" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0d5c2f] transition-colors">
                    <i class="fas fa-check-double mr-2"></i>
                    Mark All as Read
                </button>
                <button id="refresh-notifications" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0d5c2f] transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-[#0d5c2f] text-[#0d5c2f]" data-tab="all">
                All Notifications
            </button>
            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="user">
                User Actions
            </button>
        </nav>
    </div>

    <!-- Notifications List -->
    <div class="p-6">
        <div id="notifications-container">
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="notification-item border rounded-lg p-4 {{ $notification->read_at ? 'border-gray-300 bg-gray-50' : 'border-[#0d5c2f] bg-white' }}" data-notification-id="{{ $notification->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        @if(!$notification->read_at)
                                            <span class="unread-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#0d5c2f] text-white">
                                                New
                                            </span>
                                        @endif
                                        <span class="text-sm text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification->title }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $notification->message }}
                                        </p>
                                    </div>
                                    @if($notification->type === 'user')
                                        <div class="mt-2 text-xs text-gray-500">
                                            Related to your account
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    @if(!$notification->read_at)
                                        <button class="mark-read-btn inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-[#0d5c2f] bg-[#0d5c2f]/10 hover:bg-[#0d5c2f]/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0d5c2f] transition-colors" data-id="{{ $notification->id }}">
                                            Mark as Read
                                        </button>
                                    @endif
                                    <input type="checkbox" class="notification-checkbox h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded" value="{{ $notification->id }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-bell text-6xl"></i>
                    </div>
                    <p class="text-gray-500 text-lg">No notifications found</p>
                    <p class="text-gray-400 text-sm mt-1">You're all caught up!</p>
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
let currentTab = 'all';
let currentPage = 1;
let selectedNotifications = new Set();

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    updateDeleteButton();
});

function setupEventListeners() {
    // Tab switching
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const tab = this.dataset.tab;
            switchTab(tab);
        });
    });

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

function switchTab(tab) {
    currentTab = tab;
    currentPage = 1;
    
    // Update tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        if (button.dataset.tab === tab) {
            button.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]');
            button.classList.remove('border-transparent', 'text-gray-500');
        } else {
            button.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]');
            button.classList.add('border-transparent', 'text-gray-500');
        }
    });
    
    // For now, just reload the page with the tab parameter
    // In a real implementation, you'd make an AJAX call
    window.location.href = '{{ route("user.notifications.index") }}?tab=' + tab;
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
    fetch('{{ route("user.notifications.mark-as-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the notification item
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('border-[#0d5c2f]');
                notificationItem.classList.add('border-gray-300', 'bg-gray-50');
                
                const badge = notificationItem.querySelector('.unread-badge');
                if (badge) badge.remove();
                
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
            fetch('{{ route("user.notifications.mark-all-as-read") }}', {
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
        }
    }
}

function deleteSelectedNotifications() {
    if (selectedNotifications.size === 0) return;

    fetch('{{ route("user.notifications.delete") }}', {
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
    // This function will be called to update the notification count in the header
    // The header has its own update mechanism, so we just need to trigger it
    if (typeof updateNotificationCount === 'function') {
        updateNotificationCount();
    }
}
</script>
@endpush 