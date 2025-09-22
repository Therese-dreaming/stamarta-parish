@extends('layouts.priest')

@section('title', 'Ministry Details')
@section('content')
<div class="space-y-6">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-br from-[#0d5c2f] via-[#0d5c2f]/95 to-[#0d5c2f]/80 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <!-- Decorative Elements -->
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-bl-full"></div>
            <div class="absolute right-8 top-8 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="absolute right-16 bottom-8 w-8 h-8 bg-white/15 rounded-full"></div>
            
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center relative z-10 space-y-6 lg:space-y-0">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg mr-6">
                        <i class="fas fa-people-group text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $ministry->name }}</h1>
                        <div class="flex items-center space-x-4">
                            <p class="text-white/80 text-sm">Ministry Overview • Read Only Access</p>
                            @if($ministry->is_active)
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-400 rounded-full shadow-lg animate-pulse mr-2"></div>
                                    <span class="text-green-200 text-sm font-medium">Active</span>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-400 rounded-full shadow-lg mr-2"></div>
                                    <span class="text-red-200 text-sm font-medium">Inactive</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Header Stats & Actions -->
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Quick Stats -->
                    <div class="flex gap-3">
                        <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[100px] text-center">
                            <div class="text-white/80 text-xs uppercase tracking-wide font-medium">Members</div>
                            <div class="text-white text-xl font-bold">{{ $ministry->members->count() }}</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[100px] text-center">
                            <div class="text-white/80 text-xs uppercase tracking-wide font-medium">Activities</div>
                            <div class="text-white text-xl font-bold">{{ $ministry->activities->count() ?? 0 }}</div>
                        </div>
                    </div>
                    
                    <!-- Back Button -->
                    <a href="{{ route('priest.ministries.index') }}" 
                       class="px-6 py-3 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 flex items-center text-white transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Back to Ministries</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Enhanced Ministry Details -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-[#0d5c2f] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        Ministry Information
                    </h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Name & Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-tag text-blue-600 mr-2"></i>
                                <label class="text-sm font-semibold text-blue-900">Ministry Name</label>
                            </div>
                            <p class="text-blue-800 font-medium">{{ $ministry->name }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-link text-purple-600 mr-2"></i>
                                <label class="text-sm font-semibold text-purple-900">Identifier</label>
                            </div>
                            <p class="text-purple-800 font-mono text-sm">{{ $ministry->slug }}</p>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-file-text text-green-600 mr-2"></i>
                            <label class="text-sm font-semibold text-green-900">Description</label>
                        </div>
                        <div class="text-green-800 leading-relaxed">
                            {{ $ministry->description ?: 'No description provided for this ministry.' }}
                        </div>
                    </div>
                    
                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-plus text-orange-600 mr-2"></i>
                                <label class="text-sm font-semibold text-orange-900">Created Date</label>
                            </div>
                            <p class="text-orange-800 font-medium">{{ $ministry->created_at->format('F j, Y') }}</p>
                            <p class="text-orange-600 text-xs mt-1">{{ $ministry->created_at->diffForHumans() }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 border border-indigo-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-edit text-indigo-600 mr-2"></i>
                                <label class="text-sm font-semibold text-indigo-900">Last Updated</label>
                            </div>
                            <p class="text-indigo-800 font-medium">{{ $ministry->updated_at->format('F j, Y') }}</p>
                            <p class="text-indigo-600 text-xs mt-1">{{ $ministry->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Recent Activities -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-star text-white text-sm"></i>
                            </div>
                            Recent Activities
                        </h2>
                        <a href="{{ route('priest.ministries.ministry-activities.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white text-sm font-medium rounded-lg hover:bg-[#0d5c2f]/90 transition-all duration-200 hover:scale-105 shadow-md">
                            <i class="fas fa-external-link-alt mr-2 text-xs"></i>
                            View All
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($ministry->activities && $ministry->activities->count() > 0)
                        <div class="space-y-4">
                            @foreach($ministry->activities as $activity)
                            <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all duration-200 hover:-translate-y-1 group">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:shadow-xl transition-shadow">
                                                <i class="fas fa-calendar-day text-white"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="font-semibold text-gray-900 text-lg mb-2">{{ $activity->title }}</h3>
                                                <p class="text-gray-600 leading-relaxed mb-3">{{ Str::limit($activity->description, 120) }}</p>
                                                
                                                <div class="flex flex-wrap items-center gap-4 text-sm">
                                                    <div class="flex items-center text-gray-500">
                                                        <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center mr-2">
                                                            <i class="fas fa-calendar text-orange-600 text-xs"></i>
                                                        </div>
                                                        {{ $activity->start_at->format('M j, Y') }}
                                                    </div>
                                                    @if($activity->location)
                                                    <div class="flex items-center text-gray-500">
                                                        <div class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center mr-2">
                                                            <i class="fas fa-map-marker-alt text-red-600 text-xs"></i>
                                                        </div>
                                                        {{ $activity->location }}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm {{ $activity->is_public ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                            <i class="fas fa-{{ $activity->is_public ? 'globe' : 'lock' }} mr-1"></i>
                                            {{ $activity->is_public ? 'Public' : 'Internal' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <i class="fas fa-calendar-star text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">No Recent Activities</h3>
                            <p class="text-gray-500 max-w-sm mx-auto leading-relaxed">
                                This ministry hasn't scheduled any activities recently. Check back later for updates.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Enhanced Sidebar -->
        <div class="space-y-6">
            <!-- Ministry Head -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-bold text-blue-900 flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-crown text-white text-sm"></i>
                        </div>
                        Ministry Head
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($ministry->head)
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/80 flex items-center justify-center shadow-lg">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 text-lg truncate">{{ $ministry->head->name }}</h4>
                                    <p class="text-sm text-gray-600 truncate mb-2">{{ $ministry->head->email }}</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#0d5c2f] text-white shadow-sm">
                                        <i class="fas fa-crown mr-1"></i>Head
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fas fa-user-slash text-gray-400 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">No Head Assigned</h4>
                            <p class="text-gray-500 text-sm">This ministry needs a head</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced Quick Stats -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <h3 class="text-lg font-bold text-green-900 flex items-center">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-white text-sm"></i>
                        </div>
                        Ministry Stats
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-blue-900">Total Members</span>
                            </div>
                            <span class="text-2xl font-bold text-blue-800">{{ $ministry->members->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-star text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-purple-900">Activities</span>
                            </div>
                            <span class="text-2xl font-bold text-purple-800">{{ $ministry->activities->count() ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-{{ $ministry->is_active ? 'green' : 'red' }}-50 to-{{ $ministry->is_active ? 'green' : 'red' }}-100 rounded-xl p-4 border border-{{ $ministry->is_active ? 'green' : 'red' }}-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-{{ $ministry->is_active ? 'green' : 'red' }}-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-{{ $ministry->is_active ? 'check-circle' : 'times-circle' }} text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-{{ $ministry->is_active ? 'green' : 'red' }}-900">Status</span>
                            </div>
                            <span class="text-lg font-bold text-{{ $ministry->is_active ? 'green' : 'red' }}-800">
                                {{ $ministry->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                    <h3 class="text-lg font-bold text-orange-900 flex items-center">
                        <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bolt text-white text-sm"></i>
                        </div>
                        Quick Actions
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('priest.ministries.members.index', $ministry) }}" 
                           class="group flex items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl"
                           title="View Ministry Members">
                            <i class="fas fa-users text-2xl group-hover:scale-110 transition-transform"></i>
                        </a>
                        
                        <a href="{{ route('priest.ministries.ministry-activities.index') }}" 
                           class="group flex items-center justify-center p-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl"
                           title="View All Ministry Activities">
                            <i class="fas fa-calendar-star text-2xl group-hover:scale-110 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced animations and transitions */
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

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Enhanced hover effects */
.group:hover .group-hover\:shadow-xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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
.group:hover {
    transform: translateY(-2px) scale(1.02);
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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Enhanced card hover effects */
.hover\:-translate-y-1:hover {
    transform: translateY(-4px);
}

/* Gradient text effects */
.text-gradient {
    background: linear-gradient(135deg, #0d5c2f, #16a34a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Enhanced shadow effects */
.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.hover\:shadow-2xl:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
</style>
@endsection
