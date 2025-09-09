@extends('layouts.priest')

@section('title', 'Notifications')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600">Stay updated on your leave requests and booking assignments</p>
        </div>
        <div class="flex items-center space-x-3">
            <button id="mark-all-read" class="bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                <i class="fas fa-check-double mr-2"></i>Mark All as Read
            </button>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('priest.notifications.index') }}" 
               class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'all' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                All
                <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('priest.notifications.index', ['type' => 'unread']) }}" 
               class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'unread' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Unread
                <span class="ml-2 bg-red-100 text-red-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['unread'] }}</span>
            </a>
            <a href="{{ route('priest.notifications.index', ['type' => 'leave_actions']) }}" 
               class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'leave_actions' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Leave Actions
                <span class="ml-2 bg-blue-100 text-blue-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['leave_actions'] }}</span>
            </a>
            <a href="{{ route('priest.notifications.index', ['type' => 'booking_assignments']) }}" 
               class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'booking_assignments' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Booking Assignments
                <span class="ml-2 bg-green-100 text-green-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['booking_assignments'] }}</span>
            </a>
            <a href="{{ route('priest.notifications.index', ['type' => 'read']) }}" 
               class="flex items-center px-1 py-2 text-sm font-medium border-b-2 {{ $type === 'read' ? 'border-[#0d5c2f] text-[#0d5c2f]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Read
                <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $counts['read'] }}</span>
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow">
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                @php
                    // Determine icon and styling based on action type
                    $icon = 'fas fa-bell';
                    $iconBg = 'bg-blue-100';
                    $iconColor = 'text-blue-600';
                    
                    switch($notification->action) {
                        case 'priest_leave_approved':
                            $icon = 'fas fa-check-circle';
                            $iconBg = 'bg-green-100';
                            $iconColor = 'text-green-600';
                            break;
                        case 'priest_leave_rejected':
                            $icon = 'fas fa-times-circle';
                            $iconBg = 'bg-red-100';
                            $iconColor = 'text-red-600';
                            break;
                        case 'priest_booking_assigned':
                            $icon = 'fas fa-calendar-check';
                            $iconBg = 'bg-purple-100';
                            $iconColor = 'text-purple-600';
                            break;
                    }
                @endphp
                <div class="p-6 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50/30 border-l-4 border-[#0d5c2f]' : '' }}">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 {{ $iconBg }} rounded-full flex items-center justify-center">
                                <i class="{{ $icon }} {{ $iconColor }} text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 font-medium leading-5 mb-2">{{ $notification->message }}</p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $notification->created_at->format('M j, Y g:i A') }}</span>
                                        @if($notification->created_at->diffInHours() < 24)
                                            <span class="text-[#0d5c2f]">{{ $notification->created_at->diffForHumans() }}</span>
                                        @endif
                                        @if(!$notification->is_read)
                                            <span class="inline-block w-2 h-2 bg-[#0d5c2f] rounded-full"></span>
                                        @endif
                                    </div>
                                    @if($notification->data && count($notification->data) > 0)
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @if(isset($notification->data['leave_type']))
                                                <span class="inline-block bg-blue-100 px-2 py-1 rounded mr-2">
                                                    <i class="fas fa-calendar-alt mr-1"></i>{{ $notification->data['leave_type'] }}
                                                </span>
                                            @endif
                                            @if(isset($notification->data['start_date']))
                                                <span class="inline-block bg-green-100 px-2 py-1 rounded mr-2">
                                                    <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($notification->data['start_date'])->format('M j') }} - {{ \Carbon\Carbon::parse($notification->data['end_date'])->format('M j, Y') }}
                                                </span>
                                            @endif
                                            @if(isset($notification->data['service_name']))
                                                <span class="inline-block bg-purple-100 px-2 py-1 rounded mr-2">
                                                    <i class="fas fa-church mr-1"></i>{{ $notification->data['service_name'] }}
                                                </span>
                                            @endif
                                            @if(isset($notification->data['booking_date']))
                                                <span class="inline-block bg-indigo-100 px-2 py-1 rounded mr-2">
                                                    <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($notification->data['booking_date'])->format('M j, Y') }}
                                                </span>
                                            @endif
                                            @if(isset($notification->data['user_name']))
                                                <span class="inline-block bg-orange-100 px-2 py-1 rounded mr-2">
                                                    <i class="fas fa-user mr-1"></i>{{ $notification->data['user_name'] }}
                                                </span>
                                            @endif
                                            @if(isset($notification->data['reason']))
                                                <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2">
                                                    <i class="fas fa-comment mr-1"></i>{{ $notification->data['reason'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    @if(!$notification->is_read)
                                        <button class="mark-read-btn text-xs text-[#0d5c2f] hover:text-[#0d5c2f]/80 font-medium" data-id="{{ $notification->id }}">
                                            Mark read
                                        </button>
                                    @endif
                                    <button class="delete-btn text-xs text-red-600 hover:text-red-800 font-medium" data-id="{{ $notification->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                    <p class="text-gray-500">
                        @if($type === 'unread')
                            You have no unread notifications.
                        @elseif($type === 'read')
                            You have no read notifications.
                        @elseif($type === 'leave_actions')
                            No leave action notifications found.
                        @elseif($type === 'booking_assignments')
                            No booking assignment notifications found.
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
        <div class="flex justify-center">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark as read functionality
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            const notificationItem = this.closest('.p-6');
            
            fetch('{{ route("priest.notifications.mark-as-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    notification_ids: [notificationId]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notificationItem.classList.remove('bg-blue-50/30', 'border-l-4', 'border-[#0d5c2f]');
                    this.remove();
                    // Update unread count
                    location.reload();
                }
            });
        });
    });

    // Delete functionality
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this notification?')) {
                const notificationId = this.dataset.id;
                const notificationItem = this.closest('.p-6');
                
                fetch('{{ route("priest.notifications.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        notification_ids: [notificationId]
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notificationItem.remove();
                        // Update counts
                        location.reload();
                    }
                });
            }
        });
    });

    // Mark all as read
    document.getElementById('mark-all-read').addEventListener('click', function() {
        fetch('{{ route("priest.notifications.mark-all-as-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});
</script>
@endsection
