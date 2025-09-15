@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Priest Bookings')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/5 rounded-bl-full"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <h1 class="text-2xl font-bold text-white">Bookings for {{ $priest->name }}</h1>
                    <p class="text-white/80 mt-1 text-sm flex items-center">
                        <i class="fas fa-calendar-check mr-2"></i>All bookings assigned to this priest
                    </p>
                </div>
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.priests.index') : route('admin.priests.index') }}" 
                   class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Priests
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs / Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3">
        <div class="flex flex-wrap items-center gap-2">
            @php
                $tabs = [
                    ['key' => null, 'label' => 'All', 'icon' => 'fa-list', 'count' => $stats['total']],
                    ['key' => 'pending', 'label' => 'Pending', 'icon' => 'fa-hourglass-half', 'count' => $stats['pending']],
                    ['key' => 'acknowledged', 'label' => 'Acknowledged', 'icon' => 'fa-handshake', 'count' => $stats['acknowledged']],
                    ['key' => 'approved', 'label' => 'Approved', 'icon' => 'fa-check-circle', 'count' => $stats['approved']],
                    ['key' => 'completed', 'label' => 'Completed', 'icon' => 'fa-flag-checkered', 'count' => $stats['completed']],
                    ['key' => 'cancelled', 'label' => 'Cancelled', 'icon' => 'fa-times-circle', 'count' => $stats['cancelled']],
                    ['key' => 'rejected', 'label' => 'Rejected', 'icon' => 'fa-ban', 'count' => $stats['rejected'] ?? 0],
                ];
                $baseRoute = isset($isStaff) && $isStaff ? route('staff.priests.bookings', $priest) : route('admin.priests.bookings', $priest);
            @endphp
            @foreach($tabs as $tab)
                @php
                    $active = ($status ?? null) === $tab['key'] || (is_null($tab['key']) && empty($status));
                    $url = is_null($tab['key']) ? $baseRoute : ($baseRoute . '?status=' . $tab['key']);
                @endphp
                <a href="{{ $url }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors {{ $active ? 'bg-[#0d5c2f] text-white border-[#0d5c2f]' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                    <i class="fas {{ $tab['icon'] }} mr-2"></i>{{ $tab['label'] }}
                    <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $active ? 'bg-white/20 border-white/30 text-white' : 'bg-gray-100 text-gray-700' }}">{{ $tab['count'] }}</span>
                </a>
            @endforeach
            <div class="ml-auto flex items-center gap-2">
                <form method="GET" action="{{ $baseRoute }}" class="flex items-center">
                    @if(!empty($status))
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user or ID..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" />
                    <button type="submit" class="ml-2 px-3 py-2 bg-[#0d5c2f] text-white rounded-lg text-sm hover:bg-[#0a4a26]">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list mr-2 text-[#0d5c2f]"></i>
                Bookings List
            </h2>
        </div>
        <div class="p-4 overflow-x-auto">
            @if($bookings->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $booking->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($booking->user)->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($booking->service)->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->service_date?->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->service_time)->format('g:i A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $statusIcon = [
                                    'pending' => 'fa-hourglass-half text-yellow-600 bg-yellow-50',
                                    'acknowledged' => 'fa-handshake text-indigo-600 bg-indigo-50',
                                    'approved' => 'fa-check-circle text-green-600 bg-green-50',
                                    'completed' => 'fa-flag-checkered text-gray-700 bg-gray-100',
                                    'cancelled' => 'fa-times-circle text-red-600 bg-red-50',
                                    'rejected' => 'fa-ban text-red-700 bg-red-100',
                                ][$booking->status] ?? 'fa-question-circle text-gray-600 bg-gray-100';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ explode(' ', $statusIcon)[1] }}">
                                <i class="fas {{ explode(' ', $statusIcon)[0] }} mr-1"></i>{{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ (isset($isStaff) && $isStaff) ? route('staff.bookings.show', $booking) : route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $bookings->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-calendar-times text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings found</h3>
                <p class="text-gray-600">This priest has no assigned bookings yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection


