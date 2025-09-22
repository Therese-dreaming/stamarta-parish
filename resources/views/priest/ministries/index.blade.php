@extends('layouts.priest')

@section('title', 'View Ministries')
@section('content')
<div class="space-y-6">
    <!-- Modern Header with Stats -->
    <div class="bg-gradient-to-br from-[#0d5c2f] via-[#0d5c2f]/95 to-[#0d5c2f]/80 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <!-- Decorative Elements -->
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-bl-full"></div>
            <div class="absolute right-8 top-8 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="absolute right-16 bottom-8 w-8 h-8 bg-white/15 rounded-full"></div>
            
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center relative z-10 space-y-6 lg:space-y-0">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-people-group text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Ministry Overview</h1>
                            <p class="text-white/80 text-sm">View and explore parish ministries</p>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="flex flex-wrap gap-4">
                    <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[120px]">
                        <div class="text-white/80 text-xs uppercase tracking-wide font-medium">Total Ministries</div>
                        <div class="text-white text-2xl font-bold">{{ $ministries->count() }}</div>
                    </div>
                    <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[120px]">
                        <div class="text-white/80 text-xs uppercase tracking-wide font-medium">Active</div>
                        <div class="text-white text-2xl font-bold">{{ $ministries->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[120px]">
                        <div class="text-white/80 text-xs uppercase tracking-wide font-medium">Total Members</div>
                        <div class="text-white text-2xl font-bold">{{ $ministries->sum(function($ministry) { return $ministry->members->count(); }) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-6 py-4 rounded-r-xl shadow-sm flex items-center">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-check text-green-600 text-sm"></i>
            </div>
            <div>
                <div class="font-medium">Success!</div>
                <div class="text-sm">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <!-- Enhanced View Toggle -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="flex border-b border-gray-200">
            <button id="table-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-table mr-2"></i> Table View
            </button>
            <button id="card-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                <i class="fas fa-th-large mr-2"></i> Cards View
            </button>
        </div>
        
        <div class="p-6">
            @if($ministries->count() > 0)
                <!-- Enhanced Table View -->
                <div id="table-view" class="overflow-x-auto hidden animate-fadeIn">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ministry</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Head</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Members</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($ministries as $ministry)
                                <tr class="hover:bg-gray-50/50 transition-all duration-200 group">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/70 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                                    <i class="fas fa-people-group text-white text-lg"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-base font-semibold text-gray-900">{{ $ministry->name }}</div>
                                                <div class="text-sm text-gray-500 max-w-xs truncate">{{ $ministry->description ?: 'No description available' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($ministry->head)
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-600 text-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $ministry->head->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $ministry->head->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center">
                                                <div class="text-sm text-gray-400 italic bg-gray-100 px-3 py-2 rounded-lg">No head assigned</div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 shadow-sm">
                                            <i class="fas fa-users mr-2 text-xs"></i>{{ $ministry->members->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($ministry->is_active)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 shadow-sm">
                                                <i class="fas fa-check-circle mr-2 text-xs"></i>Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 shadow-sm">
                                                <i class="fas fa-times-circle mr-2 text-xs"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('priest.ministries.show', $ministry) }}" 
                                               class="w-10 h-10 bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg"
                                               title="View Details">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('priest.ministries.members.index', $ministry) }}" 
                                               class="w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg"
                                               title="View Members">
                                                <i class="fas fa-users text-sm"></i>
                                            </a>
                                            @if($ministry->fund)
                                            <a href="{{ route('admin.ministries.fund', $ministry) }}" 
                                               class="w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg"
                                               title="View Ledger">
                                                <i class="fas fa-chart-line text-sm"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Enhanced Cards View -->
                <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 animate-fadeIn">
                    @foreach($ministries as $ministry)
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-br from-[#0d5c2f] via-[#0d5c2f]/95 to-[#0d5c2f]/80 px-6 py-6 relative">
                            <!-- Decorative elements -->
                            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
                            <div class="absolute right-4 top-4 w-8 h-8 bg-white/10 rounded-full"></div>
                            
                            <div class="flex items-start justify-between relative z-10">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="h-12 w-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                                        <i class="fas fa-people-group text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <h3 class="text-lg font-bold text-white truncate">{{ $ministry->name }}</h3>
                                        <p class="text-white/80 text-sm">{{ $ministry->members->count() }} members</p>
                                    </div>
                                </div>
                                <div class="ml-3 flex-shrink-0">
                                    @if($ministry->is_active)
                                        <div class="w-3 h-3 bg-green-400 rounded-full shadow-lg animate-pulse" title="Active"></div>
                                    @else
                                        <div class="w-3 h-3 bg-red-400 rounded-full shadow-lg" title="Inactive"></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <!-- Description -->
                            <div class="mb-6">
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                    {{ $ministry->description ?: 'No description available for this ministry.' }}
                                </p>
                            </div>
                            
                            <!-- Ministry Head -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-semibold text-gray-900">Ministry Head</h4>
                                    @if($ministry->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </div>
                                @if($ministry->head)
                                    <div class="flex items-center bg-gray-50 rounded-xl p-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600 text-sm"></i>
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $ministry->head->name }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $ministry->head->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <i class="fas fa-user-slash text-gray-400 text-sm"></i>
                                        </div>
                                        <span class="text-sm text-gray-400">No head assigned</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('priest.ministries.show', $ministry) }}" 
                                   class="flex-1 h-12 bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg group/btn"
                                   title="View Ministry Details">
                                    <i class="fas fa-eye text-lg group-hover/btn:scale-110 transition-transform"></i>
                                </a>
                                <a href="{{ route('priest.ministries.members.index', $ministry) }}" 
                                   class="flex-1 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg group/btn"
                                   title="View Members">
                                    <i class="fas fa-users text-lg group-hover/btn:scale-110 transition-transform"></i>
                                </a>
                                @if($ministry->fund)
                                <a href="{{ route('admin.ministries.fund', $ministry) }}" 
                                   class="flex-1 h-12 bg-green-600 hover:bg-green-700 text-white rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg group/btn"
                                   title="View Financial Ledger">
                                    <i class="fas fa-chart-line text-lg group-hover/btn:scale-110 transition-transform"></i>
                                </a>
                                @else
                                <div class="flex-1 h-12 bg-gray-200 text-gray-400 rounded-xl flex items-center justify-center cursor-not-allowed"
                                     title="No financial ledger available">
                                    <i class="fas fa-chart-line text-lg opacity-50"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Enhanced Empty State -->
                <div class="text-center py-16">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-people-group text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">No Ministries Available</h3>
                    <p class="text-gray-500 max-w-md mx-auto text-lg leading-relaxed">
                        There are currently no ministries to display. Please check back later or contact the administrator.
                    </p>
                    <div class="mt-8">
                        <div class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-xl">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="text-sm">View Only Access</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableViewBtn = document.getElementById('table-view-btn');
    const cardViewBtn = document.getElementById('card-view-btn');
    const tableView = document.getElementById('table-view');
    const cardView = document.getElementById('card-view');

    // Set initial view (cards by default)
    cardViewBtn.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]', 'bg-green-50');
    cardView.classList.remove('hidden');

    // Store user preference
    const savedView = localStorage.getItem('priest-ministries-view') || 'cards';
    
    if (savedView === 'table') {
        switchToTableView();
    } else {
        switchToCardView();
    }

    tableViewBtn.addEventListener('click', function() {
        switchToTableView();
        localStorage.setItem('priest-ministries-view', 'table');
    });

    cardViewBtn.addEventListener('click', function() {
        switchToCardView();
        localStorage.setItem('priest-ministries-view', 'cards');
    });

    function switchToTableView() {
        tableView.classList.remove('hidden');
        cardView.classList.add('hidden');
        
        tableViewBtn.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]', 'bg-green-50');
        cardViewBtn.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]', 'bg-green-50');
        
        // Add animation
        tableView.style.opacity = '0';
        tableView.style.transform = 'translateY(10px)';
        setTimeout(() => {
            tableView.style.transition = 'all 0.3s ease-in-out';
            tableView.style.opacity = '1';
            tableView.style.transform = 'translateY(0)';
        }, 10);
    }

    function switchToCardView() {
        cardView.classList.remove('hidden');
        tableView.classList.add('hidden');
        
        cardViewBtn.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]', 'bg-green-50');
        tableViewBtn.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]', 'bg-green-50');
        
        // Add animation
        cardView.style.opacity = '0';
        cardView.style.transform = 'translateY(10px)';
        setTimeout(() => {
            cardView.style.transition = 'all 0.3s ease-in-out';
            cardView.style.opacity = '1';
            cardView.style.transform = 'translateY(0)';
        }, 10);
    }

    // Add tooltip functionality for icon buttons
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.position = 'relative';
        });
    });
});
</script>

<style>
.animate-fadeIn {
    animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced hover effects */
.group:hover .group-hover\:shadow-xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Pulse animation for active status */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Enhanced button hover effects */
.group\/btn:hover {
    transform: translateY(-1px) scale(1.02);
}

/* Backdrop blur support */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Glass effect for stats cards */
.bg-white\/15 {
    background-color: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
</style>
@endsection
