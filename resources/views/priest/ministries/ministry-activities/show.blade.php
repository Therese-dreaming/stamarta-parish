@extends('layouts.priest')

@section('title', 'Activity Details')

@section('content')
@include('components.toast')
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
                    <a href="{{ route('priest.ministries.ministry-activities.index') }}" 
                       class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg mr-6 hover:bg-white/30 transition-all duration-200 hover:scale-105">
                        <i class="fas fa-arrow-left text-white text-lg"></i>
                    </a>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg mr-6">
                        <i class="fas fa-calendar-star text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $requestModel->title }}</h1>
                        <div class="flex items-center space-x-4">
                            <p class="text-white/80 text-sm">Activity Details • Read Only Access</p>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-400/20 text-yellow-200 border-yellow-400/30',
                                    'approved' => 'bg-green-400/20 text-green-200 border-green-400/30',
                                    'rejected' => 'bg-red-400/20 text-red-200 border-red-400/30',
                                    'completed' => 'bg-blue-400/20 text-blue-200 border-blue-400/30'
                                ];
                                $statusIcons = [
                                    'pending' => 'fas fa-clock',
                                    'approved' => 'fas fa-check-circle',
                                    'rejected' => 'fas fa-times-circle',
                                    'completed' => 'fas fa-flag-checkered'
                                ];
                            @endphp
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-{{ $requestModel->status === 'pending' ? 'yellow' : ($requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'rejected' ? 'red' : 'blue')) }}-400 rounded-full shadow-lg animate-pulse mr-2"></div>
                                <span class="text-{{ $requestModel->status === 'pending' ? 'yellow' : ($requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'rejected' ? 'red' : 'blue')) }}-200 text-sm font-medium">{{ ucfirst($requestModel->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Header Stats & Status -->
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Budget Display -->
                    <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[140px] text-center">
                        <div class="text-white/80 text-xs uppercase tracking-wide font-medium">Budget Amount</div>
                        <div class="text-white text-2xl font-bold">₱{{ number_format($requestModel->amount, 2) }}</div>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 border {{ $statusClasses[$requestModel->status] ?? 'bg-gray-400/20 text-gray-200 border-gray-400/30' }}">
                        <div class="flex items-center justify-center">
                            <i class="{{ $statusIcons[$requestModel->status] ?? 'fas fa-question-circle' }} mr-2"></i>
                            <span class="font-medium">{{ ucfirst($requestModel->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Enhanced Activity Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-[#0d5c2f] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        Activity Information
                    </h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Title & Description -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-heading text-blue-600 mr-2"></i>
                                <label class="text-sm font-semibold text-blue-900">Activity Title</label>
                            </div>
                            <p class="text-blue-800 font-medium text-lg">{{ $requestModel->title }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-file-text text-green-600 mr-2"></i>
                                <label class="text-sm font-semibold text-green-900">Description</label>
                            </div>
                            <div class="text-green-800 leading-relaxed">
                                {{ $requestModel->description ?: 'No description provided for this activity.' }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Budget & Ministry -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-peso-sign text-emerald-600 mr-2"></i>
                                <label class="text-sm font-semibold text-emerald-900">Budget Amount</label>
                            </div>
                            <p class="text-emerald-800 font-bold text-xl">₱{{ number_format($requestModel->amount, 2) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-users text-purple-600 mr-2"></i>
                                <label class="text-sm font-semibold text-purple-900">Ministry</label>
                            </div>
                            <p class="text-purple-800 font-medium">{{ $requestModel->ministry->name }}</p>
                        </div>
                    </div>
                    
                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-plus text-orange-600 mr-2"></i>
                                <label class="text-sm font-semibold text-orange-900">Submitted Date</label>
                            </div>
                            <p class="text-orange-800 font-medium">{{ $requestModel->created_at->format('F j, Y') }}</p>
                            <p class="text-orange-600 text-xs mt-1">{{ $requestModel->created_at->format('h:i A') }} • {{ $requestModel->created_at->diffForHumans() }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 border border-indigo-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-edit text-indigo-600 mr-2"></i>
                                <label class="text-sm font-semibold text-indigo-900">Last Updated</label>
                            </div>
                            <p class="text-indigo-800 font-medium">{{ $requestModel->updated_at->format('F j, Y') }}</p>
                            <p class="text-indigo-600 text-xs mt-1">{{ $requestModel->updated_at->format('h:i A') }} • {{ $requestModel->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Admin Response -->
            @if($requestModel->admin_response)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-comment-alt text-white text-sm"></i>
                        </div>
                        Admin Response
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-user-shield text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-blue-800 leading-relaxed mb-3">{{ $requestModel->admin_response }}</div>
                                @if($requestModel->reviewed_at)
                                    <div class="flex items-center text-blue-600 text-sm">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>Reviewed on {{ $requestModel->reviewed_at->format('F j, Y') }} at {{ $requestModel->reviewed_at->format('h:i A') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Enhanced Supporting Documents -->
            @if($requestModel->supporting_documents)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-paperclip text-white text-sm"></i>
                        </div>
                        Supporting Documents
                    </h2>
                </div>
                
                <div class="p-6">
                    @php
                        $documents = json_decode($requestModel->supporting_documents, true) ?? [];
                    @endphp
                    
                    @if(!empty($documents))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($documents as $document)
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all duration-200 hover:-translate-y-1 group">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                            <i class="fas fa-file-alt text-white text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate mb-1">
                                            {{ basename($document) }}
                                        </p>
                                        <p class="text-xs text-gray-500">Supporting Document</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ Storage::url($document) }}" 
                                           target="_blank" 
                                           class="w-10 h-10 bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg"
                                           title="Open Document">
                                            <i class="fas fa-external-link-alt text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">No Documents Attached</h3>
                            <p class="text-gray-500 max-w-sm mx-auto leading-relaxed">
                                This activity request doesn't have any supporting documents attached.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Enhanced Sidebar -->
        <div class="space-y-6">
            <!-- Ministry Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-bold text-blue-900 flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>
                        Ministry Information
                    </h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-tag text-gray-600 mr-2"></i>
                            <label class="text-sm font-semibold text-gray-700">Ministry Name</label>
                        </div>
                        <p class="text-gray-900 font-medium">{{ $requestModel->ministry->name }}</p>
                    </div>
                    
                    @if($requestModel->ministry->head)
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-user-crown text-gray-600 mr-2"></i>
                            <label class="text-sm font-semibold text-gray-700">Ministry Head</label>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0d5c2f]/80 flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $requestModel->ministry->head->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $requestModel->ministry->head->email }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="bg-gradient-to-br from-{{ $requestModel->ministry->is_active ? 'green' : 'red' }}-50 to-{{ $requestModel->ministry->is_active ? 'green' : 'red' }}-100 rounded-xl p-4 border border-{{ $requestModel->ministry->is_active ? 'green' : 'red' }}-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-{{ $requestModel->ministry->is_active ? 'check-circle' : 'times-circle' }} text-{{ $requestModel->ministry->is_active ? 'green' : 'red' }}-600 mr-2"></i>
                                <label class="text-sm font-semibold text-{{ $requestModel->ministry->is_active ? 'green' : 'red' }}-900">Ministry Status</label>
                            </div>
                            <span class="text-{{ $requestModel->ministry->is_active ? 'green' : 'red' }}-800 font-bold">
                                {{ $requestModel->ministry->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Activity Timeline -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <h3 class="text-lg font-bold text-green-900 flex items-center">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-history text-white text-sm"></i>
                        </div>
                        Activity Timeline
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plus text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Activity Submitted</p>
                            <p class="text-xs text-gray-500">{{ $requestModel->created_at->format('F j, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $requestModel->created_at->format('h:i A') }} • {{ $requestModel->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    @if($requestModel->reviewed_at)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 {{ $requestModel->status === 'approved' ? 'bg-green-100' : 'bg-red-100' }} rounded-xl flex items-center justify-center">
                            <i class="fas fa-{{ $requestModel->status === 'approved' ? 'check' : 'times' }} {{ $requestModel->status === 'approved' ? 'text-green-600' : 'text-red-600' }}"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Activity {{ ucfirst($requestModel->status) }}</p>
                            <p class="text-xs text-gray-500">{{ $requestModel->reviewed_at->format('F j, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $requestModel->reviewed_at->format('h:i A') }} • {{ $requestModel->reviewed_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($requestModel->status === 'completed')
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-flag-checkered text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Activity Completed</p>
                            <p class="text-xs text-gray-500">{{ $requestModel->updated_at->format('F j, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $requestModel->updated_at->format('h:i A') }} • {{ $requestModel->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
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
                        <a href="{{ route('priest.ministries.show', $requestModel->ministry) }}" 
                           class="group flex items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl"
                           title="View Ministry Details">
                            <i class="fas fa-eye text-2xl group-hover:scale-110 transition-transform"></i>
                        </a>
                        
                        <a href="{{ route('priest.ministries.members.index', $requestModel->ministry) }}" 
                           class="group flex items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl"
                           title="View Ministry Members">
                            <i class="fas fa-users text-2xl group-hover:scale-110 transition-transform"></i>
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

.hover\:scale-105:hover {
    transform: scale(1.05);
}

.hover\:-translate-y-1:hover {
    transform: translateY(-4px);
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

/* Enhanced shadow effects */
.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.hover\:shadow-2xl:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Group hover effects for icons */
.group-hover\:scale-110:hover {
    transform: scale(1.1);
}

/* Enhanced card hover effects */
.hover\:shadow-md:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Gradient text effects */
.text-gradient {
    background: linear-gradient(135deg, #0d5c2f, #16a34a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endsection
