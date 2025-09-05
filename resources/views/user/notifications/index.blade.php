@extends('layouts.user')

@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto pt-5 pb-8">
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
                        <p class="text-gray-600">Stay updated with your activities</p>
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

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div id="notifications-container" class="p-6">
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        @php
                            // Determine icon and styling based on notification type
                            $icon = 'fas fa-bell';
                            $iconBg = 'bg-blue-100';
                            $iconColor = 'text-blue-600';
                            
                            if (str_contains(strtolower($notification->message), 'booking')) {
                                $icon = 'fas fa-calendar-check';
                                $iconBg = 'bg-green-100';
                                $iconColor = 'text-green-600';
                            } elseif (str_contains(strtolower($notification->message), 'payment')) {
                                $icon = 'fas fa-credit-card';
                                $iconBg = 'bg-purple-100';
                                $iconColor = 'text-purple-600';
                            } elseif (str_contains(strtolower($notification->message), 'approved') || str_contains(strtolower($notification->message), 'confirmed')) {
                                $icon = 'fas fa-check-circle';
                                $iconBg = 'bg-green-100';
                                $iconColor = 'text-green-600';
                            } elseif (str_contains(strtolower($notification->message), 'rejected') || str_contains(strtolower($notification->message), 'cancelled')) {
                                $icon = 'fas fa-times-circle';
                                $iconBg = 'bg-red-100';
                                $iconColor = 'text-red-600';
                            } elseif (str_contains(strtolower($notification->message), 'reminder')) {
                                $icon = 'fas fa-clock';
                                $iconBg = 'bg-orange-100';
                                $iconColor = 'text-orange-600';
                            }
                        @endphp
                        
                        <div class="notification-item border rounded-xl p-5 hover:shadow-md transition-all duration-200 {{ $notification->read_at ? 'border-gray-200 bg-gray-50/50' : 'border-[#0d5c2f] bg-blue-50/30' }}" data-notification-id="{{ $notification->id }}">
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
                                                    <span class="new-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#0d5c2f] text-white">
                                                        New
                                                    </span>
                                                @endif
                                                <span class="text-sm text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            
                                            <h3 class="text-sm font-semibold text-gray-900 mb-1">
                                                {{ $notification->title }}
                                            </h3>
                                            
                                            <p class="text-sm text-gray-600 leading-relaxed">
                                                {{ $notification->display_message }}
                                            </p>
                                            
                                            @if($notification->type === 'user')
                                                <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                                    <i class="fas fa-user mr-1"></i>
                                                    Account related
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
                    <p class="text-gray-500">You're all caught up! Check back later for updates.</p>
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

@endsection

@push('scripts')
<script>
let currentPage = 1;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
});

function setupEventListeners() {
    // Mark all as read
    document.getElementById('mark-all-read').addEventListener('click', markAllAsRead);
    
    // Refresh notifications
    document.getElementById('refresh-notifications').addEventListener('click', function() {
        window.location.reload();
    });
    
    // Setup notification event listeners
    setupNotificationEventListeners();
}

function setupNotificationEventListeners() {
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
        body: JSON.stringify({ notification_ids: [notificationId] })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the notification item
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('border-[#0d5c2f]', 'bg-blue-50/30');
                notificationItem.classList.add('border-gray-200', 'bg-gray-50/50');
                
                // Remove the "New" badge
                const newBadge = notificationItem.querySelector('.new-badge');
                if (newBadge) {
                    newBadge.remove();
                }
                
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

function updateHeaderNotificationCount() {
    // This function will be called to update the notification count in the header
    // The header has its own update mechanism, so we just need to trigger it
    if (typeof updateNotificationCount === 'function') {
        updateNotificationCount();
    }
}
</script>
@endpush 