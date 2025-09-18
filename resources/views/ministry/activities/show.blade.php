@extends('layouts.ministry')

@section('title', $activity->title . ' - ' . $ministry->name)
@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Enhanced Header -->
    <div class="bg-[#0d5c2f] rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-white/5 rounded-tr-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="mr-6 hidden md:block">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center border-2 border-white/30 shadow-lg">
                            <i class="fas fa-calendar-alt text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <a href="{{ route('ministry.activities.index') }}" 
                               class="inline-flex items-center px-3 py-2 border border-white/30 text-sm font-medium rounded-lg text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Activities
                            </a>
                            @if($activity->is_public)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white">
                                    <i class="fas fa-globe mr-2"></i>
                                    Public Event
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white">
                                    <i class="fas fa-lock mr-2"></i>
                                    Internal Event
                                </span>
                            @endif
                        </div>
                        <h1 class="text-3xl font-bold text-white">{{ $activity->title }}</h1>
                        <p class="text-white/80 mt-2">{{ $ministry->name }} Activity Details</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    @if($activity->estimated_budget)
                        <div class="text-3xl font-bold">₱{{ number_format($activity->estimated_budget, 2) }}</div>
                        <div class="text-sm opacity-80">Estimated Budget</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Activity Information -->
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
                            <h3 class="text-lg font-semibold text-gray-900">{{ $activity->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $activity->description ?: 'No description provided.' }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-play text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">Start Date & Time</label>
                                        <p class="text-sm text-gray-900 font-bold">{{ $activity->start_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($activity->end_at)
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-stop text-red-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">End Date & Time</label>
                                        <p class="text-sm text-gray-900 font-bold">{{ $activity->end_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($activity->location)
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-map-marker-alt text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">Location</label>
                                        <p class="text-sm text-gray-900 font-bold">{{ $activity->location }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-{{ $activity->is_public ? 'globe' : 'lock' }} text-purple-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">Event Type</label>
                                        <p class="text-sm text-gray-900 font-bold">{{ $activity->is_public ? 'Public Event' : 'Private Event' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($activity->is_all_day)
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-center">
                                <i class="fas fa-sun text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-blue-900">All Day Event</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Budget Information -->
            @if($activity->estimated_budget || $activity->budget_breakdown)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-coins mr-2 text-[#0d5c2f]"></i>
                        Budget Information
                    </h3>
                </div>
                <div class="p-6">
                    @if($activity->estimated_budget)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-peso-sign text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Estimated Budget</label>
                                    <p class="text-lg font-semibold text-[#0d5c2f]">₱{{ number_format($activity->estimated_budget, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f]/30 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                    <i class="fas fa-info-circle text-[#0d5c2f] text-sm"></i>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Budget Status</label>
                                    @php
                                        $statusColor = match($activity->budget_status_color) {
                                            'green' => 'bg-green-100 text-green-800',
                                            'yellow' => 'bg-yellow-100 text-yellow-800',
                                            'blue' => 'bg-blue-100 text-blue-800',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        <i class="fas fa-circle mr-1 text-xs"></i>{{ $activity->budget_status_text }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($activity->budget_breakdown)
                    <div class="mt-6">
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
                                            $budgetBreakdown = is_string($activity->budget_breakdown) 
                                                ? json_decode($activity->budget_breakdown, true) 
                                                : $activity->budget_breakdown;
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
                                                @if($activity->estimated_budget && $total != $activity->estimated_budget)
                                                    <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                        <div class="flex items-center text-xs text-yellow-700">
                                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                                            <span>Breakdown total (₱{{ number_format($total, 2) }}) differs from estimated budget (₱{{ number_format($activity->estimated_budget, 2) }})</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-info-circle text-gray-400 text-lg mb-2"></i>
                                                <p class="text-sm text-gray-500">No budget breakdown available</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Liquidation Report Section -->
            {{-- Debug: Budget Request Status = {{ $activity->pendingBudgetRequest->status ?? 'null' }} --}}
            @php
                $showLiquidationSection = false;
                // Check multiple possible conditions for showing liquidation section
                if (isset($activity->pendingBudgetRequest) && $activity->pendingBudgetRequest->status === 'approved') {
                    $showLiquidationSection = true;
                } elseif (isset($activity->budgetRequest) && $activity->budgetRequest->status === 'approved') {
                    $showLiquidationSection = true;
                } elseif (method_exists($activity, 'isApproved') && $activity->isApproved()) {
                    $showLiquidationSection = true;
                }
                // For testing, always show the section
                $showLiquidationSection = true;
            @endphp
            @if($showLiquidationSection)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-500 transform hover:-translate-y-1">
                <!-- Enhanced Header with Gradient -->
                <div class="bg-gradient-to-r from-[#0d5c2f] via-[#0f6b35] to-[#0d5c2f] p-6 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/5"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-full transform translate-x-8 -translate-y-8"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-tr-full transform -translate-x-6 translate-y-6"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">Liquidation Report</h3>
                                    <p class="text-white/80 text-sm">Financial accountability documentation</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($activity->liquidation_report_path)
                                    <div class="inline-flex items-center px-3 py-1.5 bg-green-500/20 backdrop-blur-sm border border-green-400/30 rounded-full">
                                        <i class="fas fa-check-circle text-green-300 mr-2"></i>
                                        <span class="text-green-100 text-sm font-medium">Submitted</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center px-3 py-1.5 bg-amber-500/20 backdrop-blur-sm border border-amber-400/30 rounded-full">
                                        <i class="fas fa-clock text-amber-300 mr-2"></i>
                                        <span class="text-amber-100 text-sm font-medium">Pending</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    {{-- Debug: Liquidation Report Path = {{ $activity->liquidation_report_path ?? 'null' }} --}}
                    @if($activity->liquidation_report_path)
                        <!-- Existing Liquidation Report - Enhanced Design -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200/60 rounded-2xl p-6 mb-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start">
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                        <i class="fas fa-file-check text-white text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <h4 class="text-lg font-bold text-green-900 mr-3">Report Successfully Submitted</h4>
                                            <div class="inline-flex items-center px-2 py-1 bg-green-100 border border-green-300 rounded-full">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                                <span class="text-green-700 text-xs font-medium">Verified</span>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex items-center text-sm text-green-700">
                                                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                                                <span class="font-medium">Submitted:</span>
                                                <span class="ml-2">{{ $activity->liquidation_submitted_at ? $activity->liquidation_submitted_at->format('F d, Y \\a\\t g:i A') : 'Unknown date' }}</span>
                                            </div>
                                            @if($activity->liquidation_notes)
                                            <div class="flex items-start text-sm text-green-700 mt-3">
                                                <i class="fas fa-sticky-note mr-2 text-green-600 mt-0.5"></i>
                                                <div>
                                                    <span class="font-medium">Notes:</span>
                                                    <p class="mt-1 text-green-600 italic">"{{ $activity->liquidation_notes }}"</p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-green-200/60">
                                <a href="{{ Storage::url($activity->liquidation_report_path) }}" target="_blank" 
                                   class="inline-flex items-center px-4 py-2.5 bg-white border-2 border-green-300 text-green-700 font-medium rounded-xl hover:bg-green-50 hover:border-green-400 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Report
                                </a>
                                <a href="{{ Storage::url($activity->liquidation_report_path) }}" download 
                                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="fas fa-download mr-2"></i>
                                    Download Report
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Upload Liquidation Report Form - Enhanced Design -->
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200/60 rounded-2xl p-6 mb-6 shadow-sm">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h4 class="text-lg font-bold text-amber-900 mr-3">Liquidation Report Required</h4>
                                        <div class="inline-flex items-center px-2 py-1 bg-amber-100 border border-amber-300 rounded-full">
                                            <div class="w-2 h-2 bg-amber-500 rounded-full mr-2 animate-pulse"></div>
                                            <span class="text-amber-700 text-xs font-medium">Action Needed</span>
                                        </div>
                                    </div>
                                    <p class="text-amber-700 text-sm leading-relaxed">
                                        Please submit the liquidation report for this approved activity to complete the financial accountability process.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced Upload Form -->
                        <form action="{{ route('ministry.activities.upload-liquidation', $activity) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <!-- File Upload Section -->
                            <div class="bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-300 hover:border-[#0d5c2f] transition-colors duration-300">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-[#0d5c2f]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-cloud-upload-alt text-[#0d5c2f] text-2xl"></i>
                                    </div>
                                    <label for="liquidation_report" class="block text-lg font-semibold text-gray-900 mb-2 cursor-pointer">
                                        Upload Liquidation Report
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <p class="text-sm text-gray-600 mb-4">Drag and drop your file here, or click to browse</p>
                                </div>
                                
                                <input type="file" 
                                       id="liquidation_report" 
                                       name="liquidation_report" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xlsx,.xls"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-[#0d5c2f] file:to-[#0f6b35] file:text-white hover:file:from-[#0a4a26] hover:file:to-[#0d5c2f] file:shadow-md hover:file:shadow-lg file:transition-all file:duration-200"
                                       required>
                                
                                <!-- File Info -->
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                    <div class="bg-white rounded-xl p-3 border border-gray-200">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-file-alt mr-2 text-[#0d5c2f]"></i>
                                            <span class="font-medium">Accepted formats:</span>
                                        </div>
                                        <p class="text-gray-500 mt-1">PDF, JPG, PNG, DOC, DOCX, XLS, XLSX</p>
                                    </div>
                                    <div class="bg-white rounded-xl p-3 border border-gray-200">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-weight-hanging mr-2 text-[#0d5c2f]"></i>
                                            <span class="font-medium">Maximum size:</span>
                                        </div>
                                        <p class="text-gray-500 mt-1">10MB per file</p>
                                    </div>
                                </div>
                                
                                @error('liquidation_report')
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                        <p class="text-red-600 text-sm flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Notes Section -->
                            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                                <label for="liquidation_notes" class="block text-lg font-semibold text-gray-900 mb-3">
                                    <i class="fas fa-sticky-note mr-2 text-[#0d5c2f]"></i>
                                    Additional Notes
                                    <span class="text-sm font-normal text-gray-500 ml-2">(Optional)</span>
                                </label>
                                <textarea id="liquidation_notes" 
                                          name="liquidation_notes" 
                                          rows="4"
                                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-all duration-200 resize-none"
                                          placeholder="Add any additional notes, explanations, or comments about the liquidation report..."></textarea>
                                @error('liquidation_notes')
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                        <p class="text-red-600 text-sm flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end pt-6 border-t-2 border-gray-100">
                                <button type="submit" 
                                        class="px-8 py-3 bg-gradient-to-r from-[#0d5c2f] to-[#0f6b35] text-white font-semibold rounded-xl hover:from-[#0a4a26] hover:to-[#0d5c2f] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-upload mr-2"></i>
                                    Submit Report
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            @endif

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
                            <h3 class="text-lg font-semibold text-gray-900">{{ $ministry->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $ministry->description ?: 'No description available.' }}</p>
                            @if($ministry->head)
                                <div class="mt-3 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    <span>Head: {{ $ministry->head->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Sidebar -->
        <div class="space-y-6 lg:sticky lg:top-4 self-start">
            <!-- Activity Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>
                        Activity Timeline
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Created Step -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-calendar-plus text-white text-sm"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-blue-900">Activity Created</h4>
                                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Completed</span>
                                    </div>
                                    <p class="text-xs text-blue-700 mb-2">{{ $activity->created_at->format('M d, Y \a\t g:i A') }}</p>
                                    <p class="text-xs text-blue-600">
                                        Activity has been created and is ready for budget request
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Budget Request Step -->
                        @if($activity->pendingBudgetRequest)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-file-invoice-dollar text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-yellow-900">Budget Request Submitted</h4>
                                        <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">
                                            @if($activity->pendingBudgetRequest->status === 'pending')
                                                Pending
                                            @else
                                                Completed
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-yellow-700 mb-2">
                                        @if($activity->pendingBudgetRequest->status === 'pending')
                                            Awaiting administrative approval
                                        @else
                                            {{ $activity->pendingBudgetRequest->approved_at ? $activity->pendingBudgetRequest->approved_at->format('M d, Y \a\t g:i A') : 'Request processed' }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-yellow-600">
                                        @if($activity->pendingBudgetRequest->status === 'pending')
                                            Budget request is under review
                                        @else
                                            Budget request has been {{ $activity->pendingBudgetRequest->status }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Liquidation Report Step -->
                        @if($activity->liquidation_report_path)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-file-invoice text-white text-sm"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-purple-900">Liquidation Report Submitted</h4>
                                        <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">Completed</span>
                                    </div>
                                    <p class="text-xs text-purple-700 mb-2">{{ $activity->liquidation_submitted_at ? $activity->liquidation_submitted_at->format('M d, Y \\a\\t g:i A') : 'Submission date unknown' }}</p>
                                    <p class="text-xs text-purple-600">
                                        Financial accountability documentation has been submitted
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Activity Status -->
                        @if($activity->currentBudgetRequest && $activity->currentBudgetRequest->status === 'complete')
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-flag-checkered text-white text-sm"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-green-900">Activity Completed</h4>
                                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Completed</span>
                                    </div>
                                    <p class="text-xs text-green-700 mb-2">{{ $activity->currentBudgetRequest->completed_at ? $activity->currentBudgetRequest->completed_at->format('M d, Y \\a\\t g:i A') : 'Completion date unknown' }}</p>
                                    <p class="text-xs text-green-600">
                                        Activity has been successfully completed with liquidation report submitted
                                    </p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-calendar-check text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-blue-900">Activity Status</h4>
                                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                            @if($activity->liquidation_report_path)
                                                Ready to Complete
                                            @else
                                                In Progress
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-blue-600">
                                        @if($activity->liquidation_report_path)
                                            Liquidation report submitted, ready for completion
                                        @else
                                            Activity is scheduled and ready for execution
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Activity Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                        Activity Details
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-calendar mr-2 text-[#0d5c2f]"></i>Created
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $activity->created_at->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-edit mr-2 text-[#0d5c2f]"></i>Last Updated
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $activity->updated_at->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                        @if($activity->estimated_budget)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-coins mr-2 text-[#0d5c2f]"></i>Budget Status
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $activity->budget_status_text }}</span>
                        </div>
                        @endif
                        @if($activity->pendingBudgetRequest && $activity->pendingBudgetRequest->approvedBy)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-user-check mr-2 text-[#0d5c2f]"></i>{{ $activity->pendingBudgetRequest->status === 'approved' ? 'Approved' : 'Rejected' }} By
                            </span>
                            <span class="text-xs font-medium text-gray-900">{{ $activity->pendingBudgetRequest->approvedBy->name }}</span>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-cogs mr-2 text-[#0d5c2f]"></i>
                        Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $canMarkComplete = $activity->liquidation_report_path && 
                                             isset($activity->currentBudgetRequest) && 
                                             $activity->currentBudgetRequest->status === 'approved';
                            $isAlreadyComplete = isset($activity->currentBudgetRequest) && 
                                               $activity->currentBudgetRequest->status === 'complete';
                            $isApproved = isset($activity->currentBudgetRequest) && 
                                        $activity->currentBudgetRequest->status === 'approved';
                        @endphp
                        
                        {{-- Mark as Complete Button: Only show when liquidation report is submitted and budget is approved --}}
                        @if($canMarkComplete && !$isAlreadyComplete)
                        <button type="button" 
                                onclick="openCompleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-flag-checkered mr-2"></i>Mark as Complete
                        </button>
                        @elseif($isAlreadyComplete)
                        <div class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg shadow-md">
                            <i class="fas fa-check-circle mr-2"></i>Activity Completed
                        </div>
                        @endif
                        
                        {{-- Edit Activity Button: Hide when budget is approved --}}
                        @if(!$isApproved && !$isAlreadyComplete)
                        <a href="{{ route('ministry.activities.edit', $activity) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm hover:shadow">
                            <i class="fas fa-edit mr-2"></i>Edit Activity
                        </a>
                        @endif
                        
                        {{-- Delete Activity Button: Hide when activity is complete --}}
                        @if(!$isAlreadyComplete)
                        <button type="button" 
                                onclick="openDeleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm hover:shadow">
                            <i class="fas fa-trash mr-2"></i>Delete Activity
                        </button>
                        @endif
                        
                        <a href="{{ route('ministry.activities.index') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-sm hover:shadow">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Activities
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
        <div class="bg-red-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Delete Activity</h3>
                </div>
                <button type="button" onclick="closeDeleteModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <span class="text-sm font-medium text-red-800">Action Required</span>
                    </div>
                    <p class="text-sm text-red-700">You are about to delete this ministry activity. This action cannot be undone.</p>
                </div>
                
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-calendar text-gray-600 mr-2"></i>
                        Activity Details
                    </h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Title:</span>
                            <span class="text-sm font-medium text-gray-900" id="deleteActivityTitle"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                        <i class="fas fa-trash mr-2"></i>
                        Confirm Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Complete Confirmation Modal -->
<div id="completeModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden transform transition-all">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-flag-checkered text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Mark Activity as Complete</h3>
                </div>
                <button type="button" onclick="closeCompleteModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-green-800">Ready for Completion</span>
                    </div>
                    <p class="text-sm text-green-700">You are about to mark this ministry activity as complete. This indicates that the activity has been successfully executed and all requirements have been fulfilled.</p>
                </div>
                
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-calendar text-gray-600 mr-2"></i>
                        Activity Details
                    </h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Title:</span>
                            <span class="text-sm font-medium text-gray-900" id="completeActivityTitle"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Liquidation Report:</span>
                            <span class="text-sm font-medium text-green-600 flex items-center">
                                <i class="fas fa-check-circle mr-1"></i>Submitted
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Completion Notes Form -->
                <form id="completeForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <label for="completion_notes" class="block text-sm font-semibold text-gray-900 mb-3">
                            <i class="fas fa-sticky-note mr-2 text-green-600"></i>
                            Completion Notes
                            <span class="text-xs font-normal text-gray-500 ml-2">(Optional)</span>
                        </label>
                        <textarea id="completion_notes" 
                                  name="completion_notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 resize-none"
                                  placeholder="Add any final notes about the completion of this activity..."></textarea>
                    </div>
                </form>
            </div>
            
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCompleteModal()" 
                        class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
                <button type="button" onclick="submitCompleteForm()" 
                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-flag-checkered mr-2"></i>
                    Mark as Complete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Delete Modal Functions
    function openDeleteModal(activityId, activityTitle) {
        const modal = document.getElementById('deleteModal');
        const titleSpan = document.getElementById('deleteActivityTitle');
        const form = document.getElementById('deleteForm');
        
        // Set the activity title and form action
        titleSpan.textContent = activityTitle;
        form.action = `/ministry/activities/${activityId}`;
        
        // Show the modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Add backdrop click to close
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Close modal when cancel button is clicked
    document.addEventListener('DOMContentLoaded', function() {
        const cancelBtn = document.getElementById('cancelDelete');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeDeleteModal);
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeCompleteModal();
            }
        });
    });
    
    // Complete Modal Functions
    function openCompleteModal(activityId, activityTitle) {
        const modal = document.getElementById('completeModal');
        const titleSpan = document.getElementById('completeActivityTitle');
        const form = document.getElementById('completeForm');
        
        // Set the activity title and form action
        titleSpan.textContent = activityTitle;
        form.action = `/ministry/activities/${activityId}/mark-complete`;
        
        // Show the modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Add backdrop click to close
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeCompleteModal();
            }
        });
    }
    
    function closeCompleteModal() {
        const modal = document.getElementById('completeModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Clear the form
        document.getElementById('completion_notes').value = '';
    }
    
    function submitCompleteForm() {
        // Submit the form directly (modal already serves as confirmation)
        document.getElementById('completeForm').submit();
    }
</script>
@endsection 