@extends('layouts.user')

@section('title', 'Notifications')

@section('content')
<div class="font-[Poppins] min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-8 py-8 relative">
                <div class="absolute right-0 top-0 w-24 h-24 bg-white/10 rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-tr-full"></div>
                <div class="absolute top-1/2 right-1/4 w-8 h-8 bg-white/5 rounded-full"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Notifications</h1>
                        <p class="text-white/90 text-lg">Stay updated with your booking status and important updates</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">{{ $counts['unread'] }}</div>
                            <div class="text-white/80 text-sm">Unread</div>
                        </div>
                        @if($counts['unread'] > 0)
                            <button id="mark-all-read-btn" class="inline-flex items-center px-6 py-3 bg-white/15 text-white rounded-xl hover:bg-white/25 transition-all duration-200 font-medium shadow-lg">
                                <i class="fas fa-check-double mr-2"></i>
                                Mark All Read
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 mb-8 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl">
                    <a href="{{ route('user.notifications.index', ['type' => 'all']) }}" 
                       class="flex items-center px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ $type === 'all' ? 'bg-white text-[#0d5c2f] shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                        <i class="fas fa-list mr-2"></i>
                        All
                        <span class="ml-2 bg-gray-200 text-gray-700 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $counts['all'] }}</span>
                    </a>
                    <a href="{{ route('user.notifications.index', ['type' => 'unread']) }}" 
                       class="flex items-center px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ $type === 'unread' ? 'bg-white text-[#0d5c2f] shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                        <i class="fas fa-envelope mr-2"></i>
                        Unread
                        <span class="ml-2 bg-red-100 text-red-700 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $counts['unread'] }}</span>
                    </a>
                    <a href="{{ route('user.notifications.index', ['type' => 'read']) }}" 
                       class="flex items-center px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ $type === 'read' ? 'bg-white text-[#0d5c2f] shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                        <i class="fas fa-check-circle mr-2"></i>
                        Read
                        <span class="ml-2 bg-green-100 text-green-700 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $counts['read'] }}</span>
                    </a>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="p-6">
                @forelse($notifications as $notification)
                    <div class="mb-6 last:mb-0">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 {{ !$notification->is_read ? 'ring-2 ring-[#0d5c2f]/20 border-[#0d5c2f]/30' : '' }}">
                            <div class="p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        @php
                                            $icon = 'fas fa-bell';
                                            $iconBg = 'bg-blue-100';
                                            $iconColor = 'text-blue-600';
                                            
                                            switch($notification->action) {
                                                case 'booking_created':
                                                    $icon = 'fas fa-calendar-plus';
                                                    $iconBg = 'bg-green-100';
                                                    $iconColor = 'text-green-600';
                                                    break;
                                                case 'booking_acknowledged':
                                                    $icon = 'fas fa-check-circle';
                                                    $iconBg = 'bg-yellow-100';
                                                    $iconColor = 'text-yellow-600';
                                                    break;
                                                case 'booking_rejected':
                                                    $icon = 'fas fa-times-circle';
                                                    $iconBg = 'bg-red-100';
                                                    $iconColor = 'text-red-600';
                                                    break;
                                                case 'payment_submitted':
                                                    $icon = 'fas fa-credit-card';
                                                    $iconBg = 'bg-purple-100';
                                                    $iconColor = 'text-purple-600';
                                                    break;
                                                case 'payment_verified':
                                                    $icon = 'fas fa-check-double';
                                                    $iconBg = 'bg-green-100';
                                                    $iconColor = 'text-green-600';
                                                    break;
                                                case 'booking_approved':
                                                    $icon = 'fas fa-star';
                                                    $iconBg = 'bg-[#0d5c2f]/10';
                                                    $iconColor = 'text-[#0d5c2f]';
                                                    break;
                                            }
                                        @endphp
                                        <div class="w-12 h-12 {{ $iconBg }} rounded-xl flex items-center justify-center shadow-sm">
                                            <i class="{{ $icon }} {{ $iconColor }} text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900 font-medium leading-5 mb-2">
                                                    {{ $notification->message }}
                                                </p>
                                                @if($notification->booking && $notification->booking->payment)
                                                    <div class="mb-2">
                                                        <span class="text-xs text-gray-500 font-medium">Total Fee:</span>
                                                        <span class="text-xs text-[#0d5c2f] font-semibold ml-1">
                                                            {{ $notification->booking->payment->formatted_total_fee }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex items-center text-xs text-gray-500">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </div>
                                                    @if($notification->booking)
                                                        <div class="flex items-center text-xs text-[#0d5c2f] font-medium">
                                                            <i class="fas fa-hashtag mr-1"></i>
                                                            Booking #{{ $notification->booking->id }}
                                                        </div>
                                                    @endif
                                                    @if(!$notification->is_read)
                                                        <div class="flex items-center text-xs text-[#0d5c2f] font-medium">
                                                            <div class="w-2 h-2 bg-[#0d5c2f] rounded-full mr-1"></div>
                                                            New
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                @if(!$notification->is_read)
                                                    <button class="mark-read-btn inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white text-sm font-medium rounded-lg hover:bg-[#0d5c2f]/90 transition-colors duration-200 shadow-sm" 
                                                            data-id="{{ $notification->id }}">
                                                        <i class="fas fa-check mr-2"></i>
                                                        Mark Read
                                                    </button>
                                                @else
                                                    <div class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg">
                                                        <i class="fas fa-check mr-2"></i>
                                                        Read
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">No notifications</h3>
                        <p class="text-gray-500 text-lg max-w-md mx-auto">
                            @if($type === 'unread')
                                You're all caught up! No unread notifications.
                            @elseif($type === 'read')
                                No read notifications yet.
                            @else
                                You don't have any notifications yet. We'll notify you about important updates here.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Mark as Read Modal -->
<div id="mark-read-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 bg-[#0d5c2f]/10 rounded-full mx-auto mb-4">
                    <i class="fas fa-check text-[#0d5c2f] text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Mark as Read</h3>
                <p class="text-gray-600 text-center mb-6">Are you sure you want to mark this notification as read?</p>
                <div class="flex space-x-3">
                    <button id="cancel-mark-read" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Cancel
                    </button>
                    <button id="confirm-mark-read" class="flex-1 px-4 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors font-medium">
                        Mark as Read
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentNotificationId = null;
    const modal = document.getElementById('mark-read-modal');
    const modalContent = document.getElementById('modal-content');

    // Modal functions
    function showModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Mark all as read
    document.getElementById('mark-all-read-btn')?.addEventListener('click', function() {
        if (confirm('Mark all notifications as read?')) {
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
                    location.reload();
                }
            });
        }
    });

    // Mark individual notification as read with modal
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentNotificationId = this.dataset.id;
            showModal();
        });
    });

    // Modal event listeners
    document.getElementById('cancel-mark-read').addEventListener('click', hideModal);
    document.getElementById('confirm-mark-read').addEventListener('click', function() {
        if (currentNotificationId) {
            fetch('{{ route("user.notifications.mark-as-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    notification_ids: [currentNotificationId]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideModal();
                    location.reload();
                }
            });
        }
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideModal();
        }
    });
});
</script>
@endsection
