@extends('layouts.user')

@section('title', 'Church Calendar')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
        <!-- Header -->
        <div class="relative mb-4 overflow-hidden rounded-lg bg-[#0d5c2f] shadow-lg">
            <!-- Simple decorative element -->
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
            
            <div class="relative z-10 p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Title Section -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-700 shadow-lg">
                                <i class="fas fa-calendar-alt text-sm text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-white tracking-tight">
                                    Church Calendar
                                </h1>
                                <p class="text-green-100 text-xs">View all scheduled events and activities</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Section -->
                    <div class="flex flex-col sm:items-end space-y-2">
                        <div class="text-lg font-semibold text-white">{{ $start->format('F Y') }}</div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('calendar.index', ['start' => $start->copy()->subMonth()->startOfMonth()->toDateString(), 'end' => $start->copy()->subMonth()->endOfMonth()->toDateString()]) }}" 
                               class="flex items-center justify-center h-8 px-3 rounded-lg bg-green-700 hover:bg-green-600 transition-colors duration-200 text-white text-sm">
                                <i class="fas fa-chevron-left mr-1"></i>
                                <span>Prev</span>
                            </a>
                            <a href="{{ route('calendar.index') }}" 
                               class="flex items-center justify-center h-8 w-8 rounded-lg bg-green-600 hover:bg-green-500 transition-colors duration-200 text-white">
                                <i class="fas fa-calendar-day text-xs"></i>
                            </a>
                            <a href="{{ route('calendar.index', ['start' => $start->copy()->addMonth()->startOfMonth()->toDateString(), 'end' => $start->copy()->addMonth()->endOfMonth()->toDateString()]) }}" 
                               class="flex items-center justify-center h-8 px-3 rounded-lg bg-green-700 hover:bg-green-600 transition-colors duration-200 text-white text-sm">
                                <span>Next</span>
                                <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced Filter Section -->
                @php
                    $activeTypes = request()->query('types', ['booking','parochial','ministry']);
                    if (!is_array($activeTypes)) { $activeTypes = [$activeTypes]; }
                    $legend = [
                        ['key' => 'booking', 'label' => 'Bookings', 'icon' => 'fa-bookmark', 'color' => '#2563eb', 'bg' => 'bg-blue-600'],
                        ['key' => 'parochial', 'label' => 'Parochial', 'icon' => 'fa-church', 'color' => '#f59e0b', 'bg' => 'bg-amber-500'],
                        ['key' => 'ministry', 'label' => 'Ministry', 'icon' => 'fa-hand-holding-heart', 'color' => '#10b981', 'bg' => 'bg-emerald-600'],
                        ['key' => 'multiple', 'label' => 'Multiple Events', 'icon' => 'fa-calendar-check', 'color' => '#ef4444', 'bg' => 'bg-red-600'],
                    ];
                @endphp
                <div class="mt-4 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-white/90">Filters:</span>
                        @foreach($legend as $item)
                            @if($item['key'] != 'multiple')
                                @php $on = in_array($item['key'], $activeTypes, true); @endphp
                                <button type="button"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-200 {{ $on ? 'bg-white text-gray-800' : 'bg-green-700 text-white hover:bg-green-600' }}"
                                        data-filter="{{ $item['key'] }}">
                                    <span class="w-2 h-2 rounded-full mr-1" style="background-color: {{ $item['color'] }}"></span>
                                    <i class="fas {{ $item['icon'] }} mr-1 text-xs"></i>{{ $item['label'] }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Calendar Grid -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-3 overflow-hidden">
            <div class="grid grid-cols-7 gap-2">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dow)
                    <div class="text-center text-xs font-semibold text-gray-700 py-1 border-b-2 border-[#0d5c2f]">{{ $dow }}</div>
                @endforeach
                @php
                    $monthStart = $start->copy()->startOfMonth();
                    $monthEnd = $start->copy()->endOfMonth();
                    $firstWeekDay = (int) $monthStart->format('w');
                    $daysInMonth = (int) $monthEnd->format('j');
                    $eventMap = collect($events)->groupBy('date');
                @endphp

                @for($i = 0; $i < $firstWeekDay; $i++)
                    <div class="p-2 border border-gray-200 bg-gray-50 rounded-lg opacity-50"></div>
                @endfor

                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $dateStr = $monthStart->copy()->day($d)->format('Y-m-d');
                        $items = $eventMap->get($dateStr, collect());
                        $isToday = $dateStr === now()->format('Y-m-d');
                        
                        // Solid color styling based on events
                        $bgColor = 'bg-white';
                        $textColor = 'text-gray-700';
                        $hoverEffect = 'hover:bg-gray-50';
                        $borderColor = 'border-gray-200';
                        
                        if ($items->count() > 0) {
                            if ($items->count() > 1) {
                                // Multiple events - use red
                                $bgColor = 'bg-red-500';
                                $textColor = 'text-white';
                                $hoverEffect = 'hover:bg-red-600';
                                $borderColor = 'border-red-400';
                            } else {
                                // Single event - use its type color
                                $event = $items->first();
                                
                                if ($event['type'] === 'booking') {
                                    $bgColor = 'bg-blue-500';
                                    $textColor = 'text-white';
                                    $hoverEffect = 'hover:bg-blue-600';
                                    $borderColor = 'border-blue-400';
                                } elseif ($event['type'] === 'parochial') {
                                    $bgColor = 'bg-amber-500';
                                    $textColor = 'text-white';
                                    $hoverEffect = 'hover:bg-amber-600';
                                    $borderColor = 'border-amber-400';
                                } else {
                                    $bgColor = 'bg-emerald-500';
                                    $textColor = 'text-white';
                                    $hoverEffect = 'hover:bg-emerald-600';
                                    $borderColor = 'border-emerald-400';
                                }
                            }
                        }
                    @endphp
                    <button type="button"
                            class="day-tile text-left p-3 rounded-lg min-h-[100px] transition-colors duration-200 {{ $bgColor }} {{ $textColor }} {{ $hoverEffect }} {{ $isToday ? 'ring-2 ring-blue-400' : '' }} border-2 {{ $borderColor }}"
                            data-date="{{ $dateStr }}"
                            data-events='@json($items->values())'>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-base font-bold">{{ $d }}</span>
                            @if($items->count() > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-white/20 text-xs font-medium">
                                    {{ $items->count() }}
                                </span>
                            @endif
                        </div>
                        @if($items->count() > 0)
                            <div class="text-center mt-4">
                                <span class="text-xs font-medium flex items-center justify-center">
                                    <i class="fas fa-eye mr-1"></i> View events
                                </span>
                            </div>
                        @else
                            <div class="text-xs text-center mt-4 opacity-70">No events</div>
                        @endif
                    </button>
                @endfor
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const active = new Set(['booking','parochial','ministry']);

    // Simple filter functionality
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-filter');
            
            if (active.has(key)) {
                active.delete(key);
                btn.classList.remove('bg-white', 'text-gray-800');
                btn.classList.add('bg-green-700', 'text-white');
            } else {
                active.add(key);
                btn.classList.add('bg-white', 'text-gray-800');
                btn.classList.remove('bg-green-700', 'text-white');
            }
            
            // Update tile visibility
            document.querySelectorAll('.day-tile').forEach(tile => {
                const events = JSON.parse(tile.getAttribute('data-events') || '[]');
                const hasVisibleEvent = events.some(ev => active.has(ev.type));
                
                if (events.length > 0 && !hasVisibleEvent) {
                    tile.classList.add('opacity-50');
                } else {
                    tile.classList.remove('opacity-50');
                }
            });
        });
    });

    // Simple Modal System
    const modal = createModal();
    document.body.appendChild(modal.wrapper);

    document.querySelectorAll('.day-tile').forEach(tile => {
        tile.addEventListener('click', () => {
            const date = tile.getAttribute('data-date');
            let events = [];
            try { events = JSON.parse(tile.getAttribute('data-events')) || []; } catch (e) {}
            
            const filteredEvents = events.filter(ev => active.has(ev.type));
            modal.open(date, filteredEvents);
        });
    });

    function createModal() {
        const wrapper = document.createElement('div');
        wrapper.className = 'fixed inset-0 z-50 hidden';
        wrapper.innerHTML = `
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300 opacity-0" data-close></div>
            <div class="absolute inset-x-0 bottom-0 md:inset-0 md:flex md:items-center md:justify-center transition-all duration-300 transform translate-y-8 opacity-0">
                <div class="bg-white md:rounded-xl md:shadow-xl md:border md:border-gray-200 w-full md:max-w-xl max-h-[90vh] overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-[#0d5c2f] text-white">
                        <div>
                            <h3 class="text-base font-semibold" id="modal-title">Selected Date</h3>
                            <p class="text-xs text-green-100">Events scheduled for this date</p>
                        </div>
                        <button class="w-8 h-8 inline-flex items-center justify-center rounded-md hover:bg-green-700 transition-colors duration-200" data-close>
                            <i class="fas fa-times text-white"></i>
                        </button>
                    </div>
                    <div class="p-4 space-y-3 max-h-[70vh] overflow-y-auto" id="modal-content"></div>
                </div>
            </div>
        `;

        const overlay = wrapper.querySelector('.bg-black\\/40');
        const modalContainer = wrapper.querySelector('.md\\:flex');

        wrapper.querySelectorAll('[data-close]').forEach(el => el.addEventListener('click', () => {
            overlay.style.opacity = '0';
            modalContainer.style.opacity = '0';
            modalContainer.style.transform = 'translateY(8px)';
            
            setTimeout(() => {
                wrapper.classList.add('hidden');
            }, 300);
        }));

        return {
            wrapper,
            open(date, events) {
                const title = new Date(date + 'T00:00:00');
                wrapper.querySelector('#modal-title').textContent = title.toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
                const content = wrapper.querySelector('#modal-content');
                
                if (!events || events.length === 0) {
                    content.innerHTML = '<div class="text-sm text-gray-500 p-4 text-center">No events scheduled or all events are filtered out.</div>';
                } else {
                    content.innerHTML = events.map((ev, index) => {
                        const icon = ev.type === 'booking' ? 'fa-bookmark' : (ev.type === 'parochial' ? 'fa-church' : 'fa-hand-holding-heart');
                        const time = ev.start_time ? ` • ${ev.start_time}` : '';
                        
                        let bgColor, borderColor, iconColor;
                        if (ev.type === 'booking') {
                            bgColor = 'bg-blue-50';
                            borderColor = 'border-blue-200';
                            iconColor = 'text-blue-500';
                        } else if (ev.type === 'parochial') {
                            bgColor = 'bg-amber-50';
                            borderColor = 'border-amber-200';
                            iconColor = 'text-amber-500';
                        } else {
                            bgColor = 'bg-emerald-50';
                            borderColor = 'border-emerald-200';
                            iconColor = 'text-emerald-500';
                        }
                        
                        return `
                        <div class="modal-content-item flex items-start p-4 border rounded-lg ${bgColor} ${borderColor} hover:shadow-md transition-shadow duration-200">
                            <div class="mt-1 mr-3 ${iconColor}">
                                <i class="fas ${icon} text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900">
                                    <span class="truncate">${ev.title || 'Event'}</span>
                                </div>
                                <div class="text-xs text-gray-600 mt-1 flex items-center">
                                    <span class="capitalize">${ev.type || ''}</span>
                                    ${time ? `<span class="mx-1">•</span><i class="far fa-clock mr-1"></i>${time}` : ''}
                                </div>
                                ${ev.description ? `<div class="text-xs text-gray-600 mt-2 border-t border-gray-100 pt-2">${ev.description}</div>` : ''}
                            </div>
                        </div>`;
                    }).join('');
                }
                
                // Show and animate
                wrapper.classList.remove('hidden');
                setTimeout(() => {
                    overlay.style.opacity = '1';
                    modalContainer.style.opacity = '1';
                    modalContainer.style.transform = 'translateY(0)';
                }, 10);
            }
        }
    }
});
</script>
@endpush
@endsection