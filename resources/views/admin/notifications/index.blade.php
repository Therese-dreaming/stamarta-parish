@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="font-[Poppins] min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-6 relative">
                <div class="absolute right-0 top-0 w-20 h-20 bg-white/10 rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-14 h-14 bg-white/5 rounded-tr-full"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Admin Notifications</h1>
                        <p class="text-white/90">Monitor user activities and system events</p>
                </div>
                <div class="flex items-center space-x-3">
                        <span class="text-white/80 text-sm">
                            {{ $counts['unread'] }} unread
                        </span>
                        @if($counts['unread'] > 0)
                            <button id="mark-all-read-btn" class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-colors">
                        <i class="fas fa-check-double mr-2"></i>
                        Mark All Read
                    </button>
                        @endif
                    </div>
            </div>
        </div>
    </div>

        <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex space-x-8">
                    <a href="{{ route('admin.admin.notifications.index', ['type' => 'all']) }}" 
                       class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'all' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All
                        <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['all'] }}</span>
                    </a>
                    <a href="{{ route('admin.admin.notifications.index', ['type' => 'unread']) }}" 
                       class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'unread' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Unread
                        <span class="ml-2 bg-red-100 text-red-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['unread'] }}</span>
                    </a>
                    <a href="{{ route('admin.admin.notifications.index', ['type' => 'user_actions']) }}" 
                       class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'user_actions' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        User Actions
                        <span class="ml-2 bg-blue-100 text-blue-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['user_actions'] }}</span>
                    </a>
                    <a href="{{ route('admin.admin.notifications.index', ['type' => 'staff_actions']) }}" 
                       class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'staff_actions' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Staff Actions
                        <span class="ml-2 bg-purple-100 text-purple-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['staff_actions'] }}</span>
                    </a>
                    <a href="{{ route('admin.admin.notifications.index', ['type' => 'priest_actions']) }}" 
                       class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'priest_actions' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Priest Actions
                        <span class="ml-2 bg-amber-100 text-amber-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['priest_actions'] }}</span>
                    </a>
                    <a href="{{ route('admin.admin.notifications.index', ['type' => 'read']) }}" 
                       class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'read' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Read
                        <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['read'] }}</span>
                    </a>
        </div>
    </div>

    <!-- Notifications List -->
            <div class="divide-y divide-gray-200">
                @forelse($notifications as $notification)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50/30 border-l-4 border-[#0d5c2f]' : '' }}">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                @php
                            $icon = 'fas fa-bell';
                            $iconBg = 'bg-blue-100';
                            $iconColor = 'text-blue-600';
                            
                                    switch($notification->action) {
                                        case 'user_booking_created':
                                            $icon = 'fas fa-calendar-plus';
                                $iconBg = 'bg-green-100';
                                $iconColor = 'text-green-600';
                                            break;
                                        case 'user_payment_submitted':
                                            $icon = 'fas fa-credit-card';
                                $iconBg = 'bg-purple-100';
                                $iconColor = 'text-purple-600';
                                            break;
                                        case 'user_booking_cancelled':
                                            $icon = 'fas fa-times-circle';
                                            $iconBg = 'bg-red-100';
                                            $iconColor = 'text-red-600';
                                            break;
                                        case 'user_contact_message':
                                            $icon = 'fas fa-envelope';
                                $iconBg = 'bg-orange-100';
                                $iconColor = 'text-orange-600';
                                            break;
                                        case 'staff_booking_acknowledged':
                                            $icon = 'fas fa-check-circle';
                                            $iconBg = 'bg-blue-100';
                                            $iconColor = 'text-blue-600';
                                            break;
                                        case 'staff_booking_approved':
                                            $icon = 'fas fa-star';
                                            $iconBg = 'bg-green-100';
                                            $iconColor = 'text-green-600';
                                            break;
                                        case 'staff_booking_rejected':
                                            $icon = 'fas fa-times-circle';
                                            $iconBg = 'bg-red-100';
                                            $iconColor = 'text-red-600';
                                            break;
                                        case 'staff_page_created':
                                            $icon = 'fas fa-file-alt';
                                            $iconBg = 'bg-indigo-100';
                                            $iconColor = 'text-indigo-600';
                                            break;
                                        case 'staff_activity_created':
                                            $icon = 'fas fa-church';
                                            $iconBg = 'bg-purple-100';
                                            $iconColor = 'text-purple-600';
                                            break;
                                        case 'priest_profile_edited':
                                            $icon = 'fas fa-user-edit';
                                            $iconBg = 'bg-amber-100';
                                            $iconColor = 'text-amber-600';
                                            break;
                                        case 'priest_leave_filed':
                                            $icon = 'fas fa-calendar-times';
                                            $iconBg = 'bg-orange-100';
                                            $iconColor = 'text-orange-600';
                                            break;
                                    }
                        @endphp
                                <div class="w-10 h-10 {{ $iconBg }} rounded-full flex items-center justify-center">
                                    <i class="{{ $icon }} {{ $iconColor }} text-sm"></i>
                                </div>
                            </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                        <p class="text-sm text-gray-900 font-medium leading-5 mb-1">
                                            {{ $notification->message }}
                                        </p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                            @if($notification->booking)
                                                <span class="text-xs text-[#0d5c2f] font-medium">
                                                    Booking #{{ $notification->booking->id }}
                                                </span>
                                                @if($notification->data['user_name'] ?? false)
                                                    <span class="text-xs text-gray-600">
                                                        by {{ $notification->data['user_name'] }}
                                                    </span>
                                                @endif
                                            @endif
                                            @if(!$notification->is_read)
                                                <span class="inline-block w-2 h-2 bg-[#0d5c2f] rounded-full"></span>
                                            @endif
                                        </div>
                                        @if($notification->data)
                                            <div class="mt-2 text-xs text-gray-600">
                                                @if(isset($notification->data['staff_name']))
                                                    <span class="inline-block bg-purple-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-user-tie mr-1"></i>{{ $notification->data['staff_name'] }}
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['user_email']))
                                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-envelope mr-1"></i>{{ $notification->data['user_email'] }}
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['service_name']))
                                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-calendar mr-1"></i>{{ $notification->data['service_name'] }}
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['amount']))
                                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-peso-sign mr-1"></i>₱{{ number_format($notification->data['amount'], 2) }}
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['page_title']))
                                                    <span class="inline-block bg-indigo-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-file-alt mr-1"></i>{{ $notification->data['page_title'] }}
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['activity_title']))
                                                    <span class="inline-block bg-purple-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-church mr-1"></i>{{ $notification->data['activity_title'] }}
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['priest_name']))
                                                    <span class="inline-block bg-amber-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-cross mr-1"></i>{{ $notification->data['priest_name'] }}
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['leave_type']))
                                                    <span class="inline-block bg-orange-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-calendar-times mr-1"></i>{{ $notification->data['leave_type'] }}
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['changes']))
                                                    <span class="inline-block bg-amber-100 px-2 py-1 rounded mr-2">
                                                        <i class="fas fa-edit mr-1"></i>{{ implode(', ', $notification->data['changes']) }}
                                                    </span>
                                                @endif
                                                </div>
                                            @endif
                                        </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(!$notification->is_read)
                                            <button class="mark-read-btn text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium" 
                                                    data-id="{{ $notification->id }}">
                                                Mark read
                                                </button>
                                            @endif
                                        <button class="delete-btn text-xs text-red-600 hover:text-red-800 font-medium" 
                                                data-id="{{ $notification->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                        <p class="text-gray-500">
                            @if($type === 'unread')
                                You have no unread notifications.
                            @elseif($type === 'read')
                                You have no read notifications.
                            @elseif($type === 'user_actions')
                                No user action notifications found.
                            @elseif($type === 'staff_actions')
                                No staff action notifications found.
                            @elseif($type === 'priest_actions')
                                No priest action notifications found.
                            @else
                                You don't have any notifications yet.
                            @endif
                        </p>
                    </div>
                @endforelse
                </div>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark all as read
    document.getElementById('mark-all-read-btn')?.addEventListener('click', function() {
        if (confirm('Mark all notifications as read?')) {
            fetch('{{ route("admin.admin.notifications.mark-all-as-read") }}', {
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

    // Mark individual notification as read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;

            fetch('{{ route("admin.admin.notifications.mark-as-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
                body: JSON.stringify({
                    notification_ids: [notificationId]
                })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
                    location.reload();
                }
            });
        });
    });

    // Delete notification
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            
            if (confirm('Delete this notification?')) {
                fetch('{{ route("admin.admin.notifications.delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
                    body: JSON.stringify({
                        notification_ids: [notificationId]
                    })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    });
});
</script>
@endsection
