@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                <p class="text-gray-600 mt-1">Manage system notifications and stay updated</p>
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
            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-[#0d5c2f] text-[#0d5c2f]" data-type="all">
                <span class="flex items-center space-x-2">
                    <span>All Notifications</span>
                    <span class="notification-count bg-[#0d5c2f] text-white text-xs px-2 py-1 rounded-full" id="count-all">{{ $counts['all'] ?? 0 }}</span>
                </span>
            </button>
            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-type="admin_staff">
                <span class="flex items-center space-x-2">
                    <span>Admin/Staff</span>
                    <span class="notification-count bg-gray-400 text-white text-xs px-2 py-1 rounded-full" id="count-admin_staff">{{ $counts['admin_staff'] ?? 0 }}</span>
                </span>
            </button>
            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-type="user">
                <span class="flex items-center space-x-2">
                    <span>User</span>
                    <span class="notification-count bg-gray-400 text-white text-xs px-2 py-1 rounded-full" id="count-user">{{ $counts['user'] ?? 0 }}</span>
                </span>
            </button>
            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-type="priest">
                <span class="flex items-center space-x-2">
                    <span>Priest</span>
                    <span class="notification-count bg-gray-400 text-white text-xs px-2 py-1 rounded-full" id="count-priest">{{ $counts['priest'] ?? 0 }}</span>
                </span>
            </button>
            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-type="staff">
                <span class="flex items-center space-x-2">
                    <span>Staff</span>
                    <span class="notification-count bg-gray-400 text-white text-xs px-2 py-1 rounded-full" id="count-staff">{{ $counts['staff'] ?? 0 }}</span>
                </span>
            </button>
        </nav>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="hidden p-6">
        <div class="flex items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#0d5c2f]"></div>
            <span class="ml-3 text-gray-600">Loading notifications...</span>
        </div>
    </div>

    <!-- Notifications List -->
    <div id="notifications-container" class="p-6">
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="notification-item border rounded-lg p-4 {{ $notification->read_at ? 'border-gray-300 bg-gray-50' : 'border-[#0d5c2f] bg-white' }} hover:shadow-md transition-all duration-200" data-notification-id="{{ $notification->id }}" data-type="{{ $notification->type }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    @if(!$notification->read_at)
                                        <span class="unread-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#0d5c2f] text-white animate-pulse">
                                            New
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $notification->type === 'admin_staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($notification->type) }}
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
                                @if($notification->type === 'admin_staff')
                                    <div class="mt-2 text-xs text-gray-500">
                                        From: {{ $notification->createdBy->name ?? 'System' }}
                                    </div>
                                @endif
                                @if($notification->type === 'user' && $notification->user)
                                    <div class="mt-2 text-xs text-gray-500">
                                        User: {{ $notification->user->name }}
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
        <div id="pagination-container" class="mt-6 flex items-center justify-between px-6 pb-6">
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
let currentType = 'all';
let currentPage = 1;
let selectedNotifications = new Set();
let notificationData = {
    all: @json($notifications->items()),
    admin_staff: @json($notifications->where('type', 'admin_staff')->values()),
    user: @json($notifications->where('type', 'user')->values()),
    priest: @json($notifications->where('type', 'priest')->values()),
    staff: @json($notifications->where('type', 'staff')->values())
};

// Ensure all arrays exist
if (!notificationData.all) notificationData.all = [];
if (!notificationData.admin_staff) notificationData.admin_staff = [];
if (!notificationData.user) notificationData.user = [];
if (!notificationData.priest) notificationData.priest = [];
if (!notificationData.staff) notificationData.staff = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    updateDeleteButton();
    updateTabCounts();
});

function setupEventListeners() {
    // Tab switching
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            switchTab(type);
        });
    });

    // Mark all as read
    document.getElementById('mark-all-read').addEventListener('click', markAllAsRead);
    
    // Refresh notifications
    document.getElementById('refresh-notifications').addEventListener('click', function() {
        refreshNotifications();
    });

    // Delete modal
    document.getElementById('cancel-delete').addEventListener('click', hideDeleteModal);
    document.getElementById('confirm-delete').addEventListener('click', deleteSelected);
    
    // Setup notification event listeners
    setupNotificationEventListeners();
}

function switchTab(type) {
    currentType = type;
    currentPage = 1;
    
    // Update active tab styling
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        
        // Update count badge styling
        const countBadge = button.querySelector('.notification-count');
        countBadge.classList.remove('bg-[#0d5c2f]');
        countBadge.classList.add('bg-gray-400');
    });
    
    const activeButton = document.querySelector(`[data-type="${type}"]`);
    activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    activeButton.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]');
    
    // Update count badge styling for active tab
    const activeCountBadge = activeButton.querySelector('.notification-count');
    activeCountBadge.classList.remove('bg-gray-400');
    activeCountBadge.classList.add('bg-[#0d5c2f]');
    
    // Show loading state
    showLoading();
    
    // Load notifications for the selected type
    loadNotificationsByType(type);
}

function showLoading() {
    document.getElementById('loading-state').classList.remove('hidden');
    document.getElementById('notifications-container').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('notifications-container').classList.remove('hidden');
}

function loadNotificationsByType(type) {
    // Simulate API call delay for better UX
    setTimeout(() => {
        const notifications = notificationData[type] || [];
        renderNotifications(notifications, type);
        hideLoading();
        updateTabCounts();
    }, 300);
}

function renderNotifications(notifications, type) {
    const container = document.getElementById('notifications-container');
    
    if (!notifications || notifications.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-bell text-6xl"></i>
                </div>
                <p class="text-gray-500 text-lg">No ${type === 'all' ? '' : type.replace('_', '/')} notifications found</p>
                <p class="text-gray-400 text-sm mt-1">You're all caught up!</p>
            </div>
        `;
        return;
    }
    
    const notificationsHTML = notifications.map(notification => {
        // Safely access notification properties with fallbacks
        const isRead = notification.read_at || notification.is_read || false;
        const notificationType = notification.type || 'unknown';
        const typeClass = notificationType === 'admin_staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
        const typeLabel = notificationType === 'admin_staff' ? 'Admin/Staff' : 'User';
        const title = notification.title || 'Notification';
        const message = notification.message || 'No message available';
        const createdAt = notification.created_at || new Date().toISOString();
        const notificationId = notification.id || 'unknown';
        
        // Safely access nested properties
        const createdByName = notification.created_by?.name || notification.createdBy?.name || 'System';
        const userName = notification.user?.name || 'Unknown User';
        
        return `
            <div class="notification-item border rounded-lg p-4 ${isRead ? 'border-gray-300 bg-gray-50' : 'border-[#0d5c2f] bg-white'} hover:shadow-md transition-all duration-200" data-notification-id="${notificationId}" data-type="${notificationType}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            ${!isRead ? '<span class="unread-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#0d5c2f] text-white animate-pulse">New</span>' : ''}
                            <span class="text-sm text-gray-500">${formatDate(createdAt)}</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${typeClass}">${typeLabel}</span>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-900">${title}</p>
                            <p class="text-sm text-gray-600 mt-1">${message}</p>
                        </div>
                        ${notificationType === 'admin_staff' ? `<div class="mt-2 text-xs text-gray-500">From: ${createdByName}</div>` : ''}
                        ${notificationType === 'user' ? `<div class="mt-2 text-xs text-gray-500">User: ${userName}</div>` : ''}
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        ${!isRead ? `<button class="mark-read-btn inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-[#0d5c2f] bg-[#0d5c2f]/10 hover:bg-[#0d5c2f]/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0d5c2f] transition-colors" data-id="${notificationId}">Mark as Read</button>` : ''}
                        <input type="checkbox" class="notification-checkbox h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded" value="${notificationId}">
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = `<div class="space-y-4">${notificationsHTML}</div>`;
    
    // Re-setup event listeners for new elements
    setupNotificationEventListeners();
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
    if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)}d ago`;
    
    return date.toLocaleDateString();
}

function refreshNotifications() {
    showLoading();
    
    // Make AJAX call to refresh notifications
    fetch('{{ route("admin.notifications.index") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            notificationData = data.notifications;
            renderNotifications(notificationData[currentType] || [], currentType);
            updateTabCounts();
        }
    })
    .catch(error => {
        console.error('Error refreshing notifications:', error);
        // Fallback to page reload
        window.location.reload();
    })
    .finally(() => {
        hideLoading();
    });
}

function updateTabCounts() {
    const allCount = notificationData.all?.length || 0;
    const adminStaffCount = notificationData.admin_staff?.length || 0;
    const userCount = notificationData.user?.length || 0;
    const priestCount = notificationData.priest?.length || 0;
    const staffCount = notificationData.staff?.length || 0;
    
    const allCountElement = document.getElementById('count-all');
    const adminStaffCountElement = document.getElementById('count-admin_staff');
    const userCountElement = document.getElementById('count-user');
    const priestCountElement = document.getElementById('count-priest');
    const staffCountElement = document.getElementById('count-staff');
    
    if (allCountElement) allCountElement.textContent = allCount;
    if (adminStaffCountElement) adminStaffCountElement.textContent = adminStaffCount;
    if (userCountElement) userCountElement.textContent = userCount;
    if (priestCountElement) priestCountElement.textContent = priestCount;
    if (staffCountElement) staffCountElement.textContent = staffCount;
}

function setupNotificationEventListeners() {
    // Checkbox listeners
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

    // Mark as read listeners
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            markAsRead([notificationId]);
        });
    });
}

function markAsRead(notificationIds) {
    fetch('{{ route("admin.notifications.mark-as-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_ids: notificationIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update local data
            const currentTime = new Date().toISOString();
            notificationIds.forEach(id => {
                // Update all arrays
                if (notificationData.all) {
                    notificationData.all = notificationData.all.map(n => 
                        n.id == id ? { ...n, read_at: currentTime, is_read: true } : n
                    );
                }
                if (notificationData.admin_staff) {
                    notificationData.admin_staff = notificationData.admin_staff.map(n => 
                        n.id == id ? { ...n, read_at: currentTime, is_read: true } : n
                    );
                }
                if (notificationData.user) {
                    notificationData.user = notificationData.user.map(n => 
                        n.id == id ? { ...n, read_at: currentTime, is_read: true } : n
                    );
                }
                if (notificationData.priest) {
                    notificationData.priest = notificationData.priest.map(n => 
                        n.id == id ? { ...n, read_at: currentTime, is_read: true } : n
                    );
                }
                if (notificationData.staff) {
                    notificationData.staff = notificationData.staff.map(n => 
                        n.id == id ? { ...n, read_at: currentTime, is_read: true } : n
                    );
                }
            });
            
            // Re-render current tab
            renderNotifications(notificationData[currentType] || [], currentType);
            updateTabCounts();
        }
    })
    .catch(error => console.error('Error marking notifications as read:', error));
}

function markAllAsRead() {
    fetch('{{ route("admin.notifications.mark-all-as-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ type: currentType })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update local data
            const currentTime = new Date().toISOString();
            
            // Update all notifications in the current type
            if (notificationData[currentType]) {
                notificationData[currentType].forEach(notification => {
                    notification.read_at = currentTime;
                    notification.is_read = true;
                });
            }
            
            // Also update all notifications if we're on the "all" tab
            if (currentType === 'all' && notificationData.all) {
                notificationData.all.forEach(notification => {
                    notification.read_at = currentTime;
                    notification.is_read = true;
                });
            }
            
            // Update other arrays to keep them in sync
            if (notificationData.admin_staff) {
                notificationData.admin_staff.forEach(notification => {
                    notification.read_at = currentTime;
                    notification.is_read = true;
                });
            }
            if (notificationData.user) {
                notificationData.user.forEach(notification => {
                    notification.read_at = currentTime;
                    notification.is_read = true;
                });
            }
            if (notificationData.priest) {
                notificationData.priest.forEach(notification => {
                    notification.read_at = currentTime;
                    notification.is_read = true;
                });
            }
            if (notificationData.staff) {
                notificationData.staff.forEach(notification => {
                    notification.read_at = currentTime;
                    notification.is_read = true;
                });
            }
            
            // Re-render current tab
            renderNotifications(notificationData[currentType] || [], currentType);
            updateTabCounts();
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

function deleteSelected() {
    if (selectedNotifications.size === 0) return;

    fetch('{{ route("admin.notifications.delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            notification_ids: Array.from(selectedNotifications)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear selection
            selectedNotifications.clear();
            updateDeleteButton();
            
            // Remove deleted notifications from local data
            const deletedIds = Array.from(selectedNotifications);
            notificationData.all = notificationData.all.filter(n => !deletedIds.includes(n.id.toString()));
            notificationData.admin_staff = notificationData.admin_staff.filter(n => !deletedIds.includes(n.id.toString()));
            notificationData.user = notificationData.user.filter(n => !deletedIds.includes(n.id.toString()));
            notificationData.priest = notificationData.priest.filter(n => !deletedIds.includes(n.id.toString()));
            notificationData.staff = notificationData.staff.filter(n => !deletedIds.includes(n.id.toString()));
            
            // Re-render current tab
            renderNotifications(notificationData[currentType] || [], currentType);
            updateTabCounts();
        }
    })
    .catch(error => console.error('Error deleting notifications:', error));
}

function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}

function updateCounts() {
    // This function will be called to update the notification count in the header
    // The header has its own update mechanism, so we just need to trigger it
    if (typeof updateNotificationCount === 'function') {
        updateNotificationCount();
    }
}
</script>
@endpush 