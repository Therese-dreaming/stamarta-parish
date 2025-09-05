@extends('layouts.admin')

@section('title', 'Transaction Details')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Enhanced Header Section -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/80 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-white/5 rounded-tr-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="mr-6 hidden md:block">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center border-2 border-white/30 shadow-lg">
                            <i class="fas fa-receipt text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-chart-line mr-3"></i>
                            Transaction Details
                        </h1>
                        <p class="text-white/90 text-base">Detailed view of financial transaction and its budget impact</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Transaction #{{ $transaction->id }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold mb-1 {{ $transaction->type === 'credit' ? 'text-green-300' : 'text-red-300' }}">
                        {{ $transaction->type === 'credit' ? '+' : '-' }}₱{{ number_format($transaction->amount, 0) }}
                    </div>
                    <div class="text-sm opacity-90">{{ ucfirst($transaction->type) }} Transaction</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.budget-management.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Budget Management
                </a>
                
                @if($transaction->source_type === 'App\Models\MinistryBudgetRequest')
                    <a href="{{ route('admin.ministries.ministry-activities.show', $transaction->source) }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors shadow-lg hover:shadow-xl">
                        <i class="fas fa-eye mr-2"></i>
                        View Activity
                    </a>
                @endif
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    @if($transaction->type === 'credit')
                        <i class="fas fa-arrow-down w-3 h-3 mr-1"></i>
                    @else
                        <i class="fas fa-arrow-up w-3 h-3 mr-1"></i>
                    @endif
                    {{ ucfirst($transaction->type) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Transaction Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Overview Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-receipt mr-2 text-[#0d5c2f]"></i>
                        Transaction Overview
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Transaction Details -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-info-circle text-[#0d5c2f] mr-2"></i>
                                    Basic Information
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Amount:</span>
                                        <span class="text-lg font-bold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->type === 'credit' ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Date & Time:</span>
                                        <span class="text-sm text-gray-900">
                                            {{ $transaction->created_at->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Ministry:</span>
                                        <span class="text-sm text-gray-900">{{ $transaction->ministry->name ?? 'General' }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Entered By:</span>
                                        <span class="text-sm text-gray-900">{{ optional($transaction->enteredBy)->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Budget Impact -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-chart-line text-[#0d5c2f] mr-2"></i>
                                    Budget Impact
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Balance Before:</span>
                                        <span class="text-sm font-medium {{ $balanceBefore >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            ₱{{ number_format($balanceBefore, 2) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Balance After:</span>
                                        <span class="text-sm font-medium {{ $balanceAfter >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            ₱{{ number_format($balanceAfter, 2) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Net Change:</span>
                                        <span class="text-sm font-medium {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->type === 'credit' ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Description:</h4>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <p class="text-sm text-gray-700">{{ $transaction->description }}</p>
                        </div>
                    </div>
                    
                    <!-- Additional Details -->
                    @if($transaction->approvedBy)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Approval Details:</h4>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Approved By:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $transaction->approvedBy->name }}</span>
                                </div>
                                @if($transaction->approved_at)
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Approved At:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $transaction->approved_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Reference Number -->
                    @if($transaction->reference_no)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Reference Number:</h4>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <p class="text-sm text-gray-700 font-mono">{{ $transaction->reference_no }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Sticky -->
        <div class="lg:col-span-1 space-y-6 sticky top-6 h-fit">
            <!-- Transaction Summary -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-simple mr-2 text-[#0d5c2f]"></i>
                        Transaction Summary
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-[#0d5c2f]/5 rounded-xl p-4 text-center">
                            <span class="text-2xl font-bold text-[#0d5c2f]">{{ $transaction->id }}</span>
                            <p class="text-xs text-gray-600 mt-1">Transaction ID</p>
                        </div>
                        <div class="bg-[#0d5c2f]/5 rounded-xl p-4 text-center">
                            <span class="text-2xl font-bold text-[#0d5c2f]">
                                {{ $transaction->type === 'credit' ? '+' : '-' }}₱{{ number_format($transaction->amount, 0) }}
                            </span>
                            <p class="text-xs text-gray-600 mt-1">Amount</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-plus text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-700">Created</span>
                            </div>
                            <span class="text-sm font-medium">{{ $transaction->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-700">Time</span>
                            </div>
                            <span class="text-sm font-medium">{{ $transaction->created_at->format('g:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                <i class="fas fa-building text-[#0d5c2f] mr-2"></i>
                                <span class="text-sm text-gray-700">Ministry</span>
                            </div>
                            <span class="text-sm font-medium">{{ $transaction->ministry->name ?? 'General' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-[#0d5c2f]"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @if($transaction->source_type === 'App\Models\MinistryBudgetRequest')
                            <a href="{{ route('admin.ministries.ministry-activities.show', $transaction->source) }}" 
                               class="w-full px-4 py-2.5 bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white rounded-lg transition-colors flex items-center justify-center font-medium">
                                <i class="fas fa-eye mr-2"></i>
                                <span>View Ministry Activity</span>
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.budget-management.index') }}" 
                           class="w-full px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors flex items-center justify-center font-medium">
                            <i class="fas fa-list mr-2"></i>
                            <span>Back to Budget List</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 