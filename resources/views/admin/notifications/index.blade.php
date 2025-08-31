@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="max-w-6xl mx-auto pt-5">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-[#0d5c2f] rounded-xl flex items-center justify-center">
                        <i class="fas fa-bell text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                        <p class="text-gray-600">Monitor all system activities and user notifications</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="mark-all-read" class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white text-sm font-medium rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                        <i class="fas fa-check-double mr-2"></i>
                        Mark All Read
                    </button>
                    <button id="refresh-notifications" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-[#0d5c2f] text-[#0d5c2f]" data-type="all">
                    <span class="flex items-center space-x-2">
                        <i class="fas fa-list mr-2"></i>
                        <span>All Notifications</span>
                        <span class="notification-count bg-[#0d5c2f] text-white text-xs px-2 py-1 rounded-full" id="count-all">{{ $counts['all'] ?? 0 }}</span>
                    </span>
                </button>
                <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-type="admin_staff">
                    <span class="flex items-center space-x-2">
                        <i class="fas fa-users-cog mr-2"></i>
                        <span>Admin/Staff</span>
                        <span class="notification-count bg-gray-400 text-white text-xs px-2 py-1 rounded-full" id="count-admin_staff">{{ $counts['admin_staff'] ?? 0 }}</span>
                    </span>
                </button>
                <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-type="user">
                    <span class="flex items-center space-x-2">
                        <i class="fas fa-user mr-2"></i>
                        <span>User</span>
                        <span class="notification-count bg-gray-400 text-white text-xs px-2 py-1 rounded-full" id="count-user">{{ $counts['user'] ?? 0 }}</span>
                    </span>
                </button>
                <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-type="priest">
                    <span class="flex items-center space-x-2">
                        <i class="fas fa-cross mr-2"></i>
                        <span>Priest</span>
                        <span class="notification-count bg-gray-400 text-white text-xs px-2 py-1 rounded-full" id="count-priest">{{ $counts['priest'] ?? 0 }}</span>
                    </span>
                </button>
                <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-type="staff">
                    <span class="flex items-center space-x-2">
                        <i class="fas fa-user-tie mr-2"></i>
                        <span>Staff</span>
                        <span class="notification-count bg-gray-400 text-white text-xs px-2 py-1 rounded-full" id="count-staff">{{ $counts['staff'] ?? 0 }}</span>
                    </span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="flex items-center justify-center">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#0d5c2f]"></div>
            </div>
            <span class="text-gray-600 font-medium">Loading notifications...</span>
        </div>
    </div>

    <!-- Notifications List -->
    <div id="notifications-container" class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        @php
                            // Determine icon and styling based on notification type
                            $icon = 'fas fa-bell';
                            $iconBg = 'bg-blue-100';
                            $iconColor = 'text-blue-600';
                            $typeClass = 'bg-blue-100 text-blue-800';
                            $typeLabel = 'Admin/Staff';
                            
                            if ($notification->type === 'user') {
                                $icon = 'fas fa-user';
                                $iconBg = 'bg-green-100';
                                $iconColor = 'text-green-600';
                                $typeClass = 'bg-green-100 text-green-800';
                                $typeLabel = 'User';
                            } elseif ($notification->type === 'priest') {
                                $icon = 'fas fa-cross';
                                $iconBg = 'bg-purple-100';
                                $iconColor = 'text-purple-600';
                                $typeClass = 'bg-purple-100 text-purple-800';
                                $typeLabel = 'Priest';
                            } elseif ($notification->type === 'staff') {
                                $icon = 'fas fa-user-tie';
                                $iconBg = 'bg-orange-100';
                                $iconColor = 'text-orange-600';
                                $typeClass = 'bg-orange-100 text-orange-800';
                                $typeLabel = 'Staff';
                            }
                        @endphp
                        
                        <div class="notification-item border rounded-xl p-5 hover:shadow-md transition-all duration-200 {{ $notification->read_at ? 'border-gray-200 bg-gray-50/50' : 'border-[#0d5c2f] bg-blue-50/30' }}" data-notification-id="{{ $notification->id }}" data-type="{{ $notification->type }}">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 {{ $iconBg }} rounded-xl flex items-center justify-center">
                                        <i class="{{ $icon }} {{ $iconColor }} text-lg"></i>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                @if(!$notification->read_at)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#0d5c2f] text-white animate-pulse">
                                                        New
                                                    </span>
                                                @endif
                                                <span class="text-sm text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $typeClass }}">
                                                    {{ $typeLabel }}
                                                </span>
                                            </div>
                                            
                                            <h3 class="text-sm font-semibold text-gray-900 mb-1">
                                                {{ $notification->title }}
                                            </h3>
                                            
                                            <p class="text-sm text-gray-600 leading-relaxed">
                                                {{ $notification->display_message }}
                                            </p>
                                            
                                            @if($notification->type === 'admin_staff' && $notification->createdBy)
                                                <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                                    <i class="fas fa-user mr-1"></i>
                                                    From: {{ $notification->createdBy->name ?? 'System' }}
                                                </div>
                                            @endif
                                            
                                            @if($notification->type === 'admin_staff' && isset($notification->data['staff_name']))
                                                <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-600">
                                                    <i class="fas fa-user-tie mr-1"></i>
                                                    Staff: {{ $notification->data['staff_name'] }}
                                                </div>
                                            @endif
                                            
                                            @if($notification->type === 'user' && $notification->user)
                                                <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                                    <i class="fas fa-user mr-1"></i>
                                                    User: {{ $notification->user->name }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center space-x-3 ml-4">
                                            @if(!$notification->read_at)
                                                <button class="mark-read-btn inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-[#0d5c2f] bg-[#0d5c2f]/10 hover:bg-[#0d5c2f]/20 transition-colors" data-id="{{ $notification->id }}">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Mark Read
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
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No notifications found</h3>
                    <p class="text-gray-500">No notifications to display at the moment.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div id="pagination-container" class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        @if($notifications->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $notifications->previousPageUrl() }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" data-page="{{ $notifications->currentPage() - 1 }}">Previous</a>
                        @endif
                        
                        <span class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg">
                            Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}
                        </span>
                        
                        @if($notifications->hasMorePages())
                            <a href="{{ $notifications->nextPageUrl() }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" data-page="{{ $notifications->currentPage() + 1 }}">Next</a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">Next</span>
                        @endif
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} results
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-96 shadow-2xl rounded-xl bg-white">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Notifications</h3>
            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to delete the selected notifications? This action cannot be undone.
            </p>
            <div class="flex items-center justify-center space-x-3">
                <button id="cancel-delete" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Cancel
                </button>
                <button id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
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
            <div class="p-6">
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No notifications found</h3>
                    <p class="text-gray-500">No notifications to display at the moment.</p>
                </div>
            </div>
        `;
        return;
    }
    
    const notificationsHTML = notifications.map(notification => {
        // Safely access notification properties with fallbacks
        const isRead = notification.read_at || notification.is_read || false;
        const notificationType = notification.type || 'unknown';
        const title = notification.title || 'Notification';
        const message = notification.display_message || notification.message || 'No message available';
        const createdAt = notification.created_at || new Date().toISOString();
        const notificationId = notification.id || 'unknown';
        
        // Determine icon and styling based on notification type
        let icon = 'fas fa-bell';
        let iconBg = 'bg-blue-100';
        let iconColor = 'text-blue-600';
        let typeClass = 'bg-blue-100 text-blue-800';
        let typeLabel = 'Admin/Staff';
        
        if (notificationType === 'user') {
            icon = 'fas fa-user';
            iconBg = 'bg-green-100';
            iconColor = 'text-green-600';
            typeClass = 'bg-green-100 text-green-800';
            typeLabel = 'User';
        } else if (notificationType === 'priest') {
            icon = 'fas fa-cross';
            iconBg = 'bg-purple-100';
            iconColor = 'text-purple-600';
            typeClass = 'bg-purple-100 text-purple-800';
            typeLabel = 'Priest';
        } else if (notificationType === 'staff') {
            icon = 'fas fa-user-tie';
            iconBg = 'bg-orange-100';
            iconColor = 'text-orange-600';
            typeClass = 'bg-orange-100 text-orange-800';
            typeLabel = 'Staff';
        }
        
        // Safely access nested properties
        const createdByName = notification.created_by?.name || notification.createdBy?.name || 'System';
        const userName = notification.user?.name || 'Unknown User';
        
        return `
            <div class="notification-item border rounded-xl p-5 hover:shadow-md transition-all duration-200 ${isRead ? 'border-gray-200 bg-gray-50/50' : 'border-[#0d5c2f] bg-blue-50/30'}" data-notification-id="${notificationId}" data-type="${notificationType}">
                <div class="flex items-start space-x-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 ${iconBg} rounded-xl flex items-center justify-center">
                            <i class="${icon} ${iconColor} text-lg"></i>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    ${!isRead ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#0d5c2f] text-white animate-pulse">New</span>' : ''}
                                    <span class="text-sm text-gray-500">${formatDate(createdAt)}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${typeClass}">${typeLabel}</span>
                                </div>
                                
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">${title}</h3>
                                
                                <p class="text-sm text-gray-600 leading-relaxed">${message}</p>
                                
                                ${notificationType === 'admin_staff' ? `<div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600"><i class="fas fa-user mr-1"></i>From: ${createdByName}</div>` : ''}
                                ${notificationType === 'admin_staff' && notification.data && notification.data.staff_name ? `<div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-600"><i class="fas fa-user-tie mr-1"></i>Staff: ${notification.data.staff_name}</div>` : ''}
                                ${notificationType === 'user' ? `<div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600"><i class="fas fa-user mr-1"></i>User: ${userName}</div>` : ''}
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center space-x-3 ml-4">
                                ${!isRead ? `<button class="mark-read-btn inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-[#0d5c2f] bg-[#0d5c2f]/10 hover:bg-[#0d5c2f]/20 transition-colors" data-id="${notificationId}"><i class="fas fa-check mr-1"></i>Mark Read</button>` : ''}
                                <input type="checkbox" class="notification-checkbox h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded" value="${notificationId}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = `<div class="p-6"><div class="space-y-4">${notificationsHTML}</div></div>`;
    
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