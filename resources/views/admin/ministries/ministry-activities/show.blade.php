@extends('layouts.admin')

@section('title', 'Ministry Activity Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Enhanced Header with Gradient Background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#1a8045] rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-bl-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center">
                    <div class="mr-5 hidden md:block">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/30 shadow-lg">
                            <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <a href="{{ route('admin.ministries.ministry-activities.index') }}" 
                               class="inline-flex items-center px-3 py-2 border border-white/30 text-sm font-medium rounded-lg text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Activities
                            </a>
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-500', 'icon' => 'fa-clock'],
                                    'approved' => ['bg' => 'bg-green-500', 'icon' => 'fa-check'],
                                    'rejected' => ['bg' => 'bg-red-500', 'icon' => 'fa-times'],
                                    'complete' => ['bg' => 'bg-blue-500', 'icon' => 'fa-check-circle']
                                ];
                                $config = $statusConfig[$requestModel->status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white">
                                <i class="fas {{ $config['icon'] }} mr-2"></i>
                                {{ ucfirst($requestModel->status) }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-white">Ministry Activity #{{ $requestModel->id }}</h1>
                        <p class="text-white/80 mt-2">{{ $requestModel->purpose }}</p>
                    </div>
                </div>
                <div class="text-right text-white mt-4 md:mt-0">
                    <div class="text-3xl font-bold">₱{{ number_format($requestModel->amount, 2) }}</div>
                    <div class="text-sm opacity-80">Requested Amount</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Period Banner (if activity exists) -->
    @if($requestModel->activity)
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="flex items-center">
                    <div class="mr-4">
                        <div class="w-12 h-12 bg-[#0d5c2f] rounded-lg flex items-center justify-center shadow-sm">
                            <i class="fas fa-calendar-alt text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $requestModel->activity->title }}</h2>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $requestModel->activity->description }}</p>
                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                            <span>{{ $requestModel->activity->start_at->format('M d, Y \a\t g:i A') }}</span>
                            @if($requestModel->activity->end_at)
                                <span>•</span>
                                <span>{{ $requestModel->activity->end_at->format('M d, Y \a\t g:i A') }}</span>
                            @endif
                            @if($requestModel->activity->location)
                                <span>•</span>
                                <span>{{ $requestModel->activity->location }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-file-invoice-dollar mr-2 text-[#0d5c2f]"></i>
                        Request Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-bullseye text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Purpose</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $requestModel->purpose }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-peso-sign text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Amount Requested</label>
                                    <p class="text-lg font-semibold text-[#0d5c2f]">₱{{ number_format($requestModel->amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                                    <i class="fas fa-info-circle text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500">Details</label>
                                    <div class="text-sm text-gray-900 mt-1">
                                        @php
                                            $details = $requestModel->details;
                                            if (is_string($details)) {
                                                $decodedDetails = json_decode($details, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedDetails)) {
                                                    $details = $decodedDetails;
                                                }
                                            }
                                        @endphp
                                        
                                        @if(is_array($details) && count($details) > 0)
                                            <div class="space-y-2">
                                                @foreach($details as $item => $amount)
                                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                                        <span class="flex items-center">
                                                            <i class="fas fa-circle text-[#0d5c2f] text-xs mr-2"></i>
                                                            {{ $item }}
                                                        </span>
                                                        <span class="font-medium text-[#0d5c2f]">₱{{ number_format($amount, 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif(is_string($details) && !empty($details))
                                            <p>{{ $details }}</p>
                                        @else
                                            <p class="text-gray-500 italic">No additional details provided.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ministry Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-church mr-2 text-[#0d5c2f]"></i>
                        Ministry Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#0d5c2f] to-[#1a8045] rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-church text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $requestModel->ministry->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $requestModel->ministry->description }}</p>
                            @if($requestModel->ministry->head)
                                <div class="mt-3 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    <span>Head: {{ $requestModel->ministry->head->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            @if($requestModel->activity)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f]"></i>
                        Activity Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $requestModel->activity->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $requestModel->activity->description }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-play text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">Start Date & Time</label>
                                        <p class="text-sm text-gray-900 font-bold">{{ $requestModel->activity->start_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-stop text-red-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">End Date & Time</label>
                                        <p class="text-sm text-gray-900 font-bold">{{ $requestModel->activity->end_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($requestModel->activity->location)
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-map-marker-alt text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">Location</label>
                                        <p class="text-sm text-gray-900 font-bold">{{ $requestModel->activity->location }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-{{ $requestModel->activity->is_public ? 'globe' : 'lock' }} text-purple-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">Event Type</label>
                                        <p class="text-sm text-gray-900 font-bold">{{ $requestModel->activity->is_public ? 'Public Event' : 'Private Event' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($requestModel->activity->budget_breakdown)
                        <div class="border-t pt-6">
                            <h4 class="text-sm font-medium text-gray-500 mb-4 flex items-center">
                                <i class="fas fa-calculator mr-2 text-[#0d5c2f]"></i>
                                Budget Breakdown
                            </h4>
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                                        <i class="fas fa-list text-[#0d5c2f] text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm text-gray-900">
                                            @php
                                                $budgetBreakdown = is_string($requestModel->activity->budget_breakdown) 
                                                    ? json_decode($requestModel->activity->budget_breakdown, true) 
                                                    : $requestModel->activity->budget_breakdown;
                                                $total = 0;
                                            @endphp
                                            @if(is_array($budgetBreakdown) && count($budgetBreakdown) > 0)
                                                <div class="space-y-2">
                                                    @foreach($budgetBreakdown as $item => $amount)
                                                        @php $total += $amount; @endphp
                                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                                            <span class="flex items-center">
                                                                <i class="fas fa-circle text-[#0d5c2f] text-xs mr-2"></i>
                                                                {{ $item }}
                                                            </span>
                                                            <span class="font-medium text-[#0d5c2f]">₱{{ number_format($amount, 2) }}</span>
                                                        </div>
                                                    @endforeach
                                                    <div class="flex items-center justify-between p-3 bg-[#0d5c2f]/10 rounded-lg border-t border-[#0d5c2f]/20 mt-3">
                                                        <span class="font-semibold text-[#0d5c2f]">Total</span>
                                                        <span class="font-bold text-lg text-[#0d5c2f]">₱{{ number_format($total, 2) }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-gray-500 italic">No budget breakdown available</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Attached Files -->
            @if($requestModel->files->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-[#0d5c2f]"></i>
                        Attached Files
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($requestModel->files as $file)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-[#0d5c2f]/30 transition-all duration-300 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="w-10 h-10 rounded-lg bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                        <i class="fas fa-file text-[#0d5c2f] text-sm"></i>
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $file->original_name }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Uploaded by {{ $file->uploader->name ?? 'Unknown' }} on {{ $file->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ Storage::url($file->path) }}" target="_blank" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                    <a href="{{ Storage::url($file->path) }}" download="{{ $file->original_name }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-download mr-1"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sticky Sidebar -->
        <div class="space-y-6 lg:sticky lg:top-4 self-start">
            <!-- Request Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>
                        Request Timeline
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Submitted Step -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-file-alt text-white text-sm"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-blue-900">Request Submitted</h4>
                                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Completed</span>
                                    </div>
                                    <p class="text-xs text-blue-700 mb-2">{{ $requestModel->created_at->format('M d, Y \a\t g:i A') }}</p>
                                    <p class="text-xs text-blue-600">
                                        Ministry activity has been submitted for review
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Review Step -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-search text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-yellow-900">Under Review</h4>
                                        <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">
                                            @if($requestModel->status === 'pending')
                                                Active
                                            @else
                                                Completed
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-yellow-700 mb-2">
                                        @if($requestModel->status === 'pending')
                                            Currently being reviewed
                                        @else
                                            {{ $requestModel->approved_at ? $requestModel->approved_at->format('M d, Y \a\t g:i A') : 'Review completed' }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-yellow-600">
                                        @if($requestModel->status === 'pending')
                                            Awaiting administrative approval
                                        @else
                                            Review process completed
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Decision Step -->
                        @if($requestModel->status !== 'pending')
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-r {{ $requestModel->status === 'approved' ? 'from-green-500 to-green-600' : ($requestModel->status === 'complete' ? 'from-blue-500 to-blue-600' : 'from-red-500 to-red-600') }} rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas {{ $requestModel->status === 'approved' ? 'fa-check' : ($requestModel->status === 'complete' ? 'fa-check-circle' : 'fa-times') }} text-white text-sm"></i>
                                    </div>
                                    @if($requestModel->status === 'approved' || $requestModel->status === 'complete')
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-{{ $requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'complete' ? 'blue' : 'red') }}-50 rounded-lg p-4 border border-{{ $requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'complete' ? 'blue' : 'red') }}-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-{{ $requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'complete' ? 'blue' : 'red') }}-900">
                                            Request {{ ucfirst($requestModel->status) }}
                                        </h4>
                                        <span class="text-xs text-{{ $requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'complete' ? 'blue' : 'red') }}-600 bg-{{ $requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'complete' ? 'blue' : 'red') }}-100 px-2 py-1 rounded-full">Completed</span>
                                    </div>
                                    <p class="text-xs text-{{ $requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'complete' ? 'blue' : 'red') }}-700 mb-2">
                                        {{ $requestModel->approved_at->format('M d, Y \a\t g:i A') }}
                                        @if($requestModel->approvedBy)
                                            by {{ $requestModel->approvedBy->name }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-{{ $requestModel->status === 'approved' ? 'green' : ($requestModel->status === 'complete' ? 'blue' : 'red') }}-600">
                                        @if($requestModel->status === 'approved')
                                            Ministry activity has been approved and funds allocated
                                        @elseif($requestModel->status === 'complete')
                                            Ministry activity has been completed successfully
                                        @else
                                            Ministry activity has been rejected
                                            @if($requestModel->rejection_notes)
                                                <br><span class="font-medium">Reason: {{ $requestModel->rejection_notes }}</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Pending Decision Step -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-clock text-gray-500 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-gray-700">Decision Pending</h4>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Waiting</span>
                                    </div>
                                    <p class="text-xs text-gray-600">Awaiting administrative decision</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                        Request Details
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>Requested By
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $requestModel->requestedBy->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-calendar mr-2 text-[#0d5c2f]"></i>Request Date
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $requestModel->created_at->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                        @if($requestModel->approvedBy)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-user-check mr-2 text-[#0d5c2f]"></i>{{ $requestModel->status === 'approved' ? 'Approved' : ($requestModel->status === 'complete' ? 'Completed' : 'Rejected') }} By
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $requestModel->approvedBy->name }}</span>
                        </div>
                        @endif
                        @if($requestModel->rejection_notes)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-2 mt-0.5"></i>
                                <div>
                                    <span class="text-xs font-medium text-red-800">Rejection Reason</span>
                                    <p class="text-xs text-red-700 mt-1">{{ $requestModel->rejection_notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            @if($requestModel->status === 'pending')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-cogs mr-2 text-[#0d5c2f]"></i>
                        Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <button onclick="openApproveModal({{ $requestModel->id }}, '{{ $requestModel->purpose }}', '{{ $requestModel->ministry->name }}', {{ $requestModel->amount }})"
                                class="w-full flex items-center justify-center px-4 py-3 bg-green-800 text-white rounded-lg hover:bg-green-900 transition-colors shadow-sm hover:shadow">
                            <i class="fas fa-check mr-2"></i>Approve Activity
                        </button>
                        
                        <button onclick="openRejectModal({{ $requestModel->id }}, '{{ $requestModel->purpose }}', '{{ $requestModel->ministry->name }}', {{ $requestModel->amount }})"
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-800 text-white rounded-lg hover:bg-red-900 transition-colors shadow-sm hover:shadow">
                            <i class="fas fa-times mr-2"></i>Reject Activity
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-[#0d5c2f]"></i>
                        Ministry Budget Stats
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-[#0d5c2f]/10 to-[#1a8045]/10 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-600">Total Budget Allocated</span>
                                <span class="text-sm font-bold text-[#0d5c2f]">₱{{ number_format($requestModel->ministry->budget ?? 0, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-[#0d5c2f] h-2 rounded-full" style="width: {{ $requestModel->ministry->budget_percentage ?? 0 }}%"></div>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">Used: ₱{{ number_format($requestModel->ministry->budget_used ?? 0, 2) }}</span>
                                <span class="text-xs text-gray-500">{{ number_format($requestModel->ministry->budget_percentage ?? 0, 1) }}%</span>
                            </div>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-gray-500">Remaining: ₱{{ number_format($requestModel->ministry->budget_remaining ?? 0, 2) }}</span>
                                <span class="text-xs text-gray-500">Available</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="text-xs text-gray-500 mb-1">Approved Requests</div>
                                <div class="text-lg font-bold text-gray-900">{{ $requestModel->ministry->approved_requests_count ?? 0 }}</div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="text-xs text-gray-500 mb-1">Pending Requests</div>
                                <div class="text-lg font-bold text-gray-900">{{ $requestModel->ministry->pending_requests_count ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="bg-green-700 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-check text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Approve Ministry Activity</h3>
                </div>
                <button type="button" onclick="closeApproveModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <form id="approveForm" action="{{ route('admin.ministries.ministry-activities.approve', $requestModel) }}" method="POST">
                @csrf
                <input type="hidden" name="request_id" id="approve_request_id">
                
                <!-- Confirmation Message -->
                <div class="mb-6">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            <span class="text-sm font-medium text-green-800">Confirmation Required</span>
                        </div>
                        <p class="text-sm text-green-700">You are about to approve the following ministry activity. This action will credit the ministry's funds and cannot be undone.</p>
                    </div>
                    
                    <!-- Activity Details -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-alt text-gray-600 mr-2"></i>
                            Activity Details
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Purpose:</span>
                                <span class="text-sm font-medium text-gray-900" id="approve_purpose"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Ministry:</span>
                                <span class="text-sm font-medium text-gray-900" id="approve_ministry"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Amount:</span>
                                <span class="text-sm font-bold text-green-600" id="approve_amount"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Approval Notes -->
                <div class="mb-6">
                    <label for="approval_notes" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-sticky-note text-gray-600 mr-2"></i>
                        Approval Notes (Optional)
                    </label>
                    <textarea id="approval_notes" name="approval_notes" rows="3" 
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600 focus:border-green-600 transition-colors resize-none"
                              placeholder="Add any additional notes or comments..."></textarea>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeApproveModal()" 
                            class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-green-700 text-white rounded-xl hover:bg-green-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                        <i class="fas fa-check mr-2"></i>
                        Confirm Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="bg-red-700 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-times text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Reject Ministry Activity</h3>
                </div>
                <button type="button" onclick="closeRejectModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <form id="rejectForm" action="{{ route('admin.ministries.ministry-activities.reject', $requestModel) }}" method="POST">
                @csrf
                <input type="hidden" name="request_id" id="reject_request_id">
                
                <!-- Warning Message -->
                <div class="mb-6">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                            <span class="text-sm font-medium text-red-800">Action Required</span>
                        </div>
                        <p class="text-sm text-red-700">You are about to reject this ministry activity. Please provide a clear reason for the rejection.</p>
                    </div>
                    
                    <!-- Activity Details -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-alt text-gray-600 mr-2"></i>
                            Activity Details
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Purpose:</span>
                                <span class="text-sm font-medium text-gray-900" id="reject_purpose"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Ministry:</span>
                                <span class="text-sm font-medium text-gray-900" id="reject_ministry"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Amount:</span>
                                <span class="text-sm font-bold text-red-600" id="reject_amount"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Rejection Reason -->
                <div class="mb-6">
                    <label for="rejection_notes" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-comment-alt text-gray-600 mr-2"></i>
                        Rejection Reason <span class="text-red-500 font-bold">*</span>
                    </label>
                    <textarea id="rejection_notes" name="rejection_notes" rows="3" required
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-colors resize-none"
                              placeholder="Please provide a detailed reason for rejecting this activity..."></textarea>
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                        This field is required and will be shared with the ministry.
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-700 text-white rounded-xl hover:bg-red-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                        <i class="fas fa-times mr-2"></i>
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openApproveModal(id, purpose, ministry, amount) {
        document.getElementById('approve_request_id').value = id;
        document.getElementById('approve_purpose').textContent = purpose;
        document.getElementById('approve_ministry').textContent = ministry;
        document.getElementById('approve_amount').textContent = '₱' + amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        document.getElementById('approveModal').classList.remove('hidden');
        document.getElementById('approveModal').classList.add('flex');
    }
    
    function closeApproveModal() {
        document.getElementById('approveModal').classList.remove('flex');
        document.getElementById('approveModal').classList.add('hidden');
    }
    
    function openRejectModal(id, purpose, ministry, amount) {
        document.getElementById('reject_request_id').value = id;
        document.getElementById('reject_purpose').textContent = purpose;
        document.getElementById('reject_ministry').textContent = ministry;
        document.getElementById('reject_amount').textContent = '₱' + amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }
    
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('flex');
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
                