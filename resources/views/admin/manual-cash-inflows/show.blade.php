@extends('layouts.admin')

@section('title', 'Cash Inflow Details')

@section('content')
@include('components.toast')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <!-- Enhanced Header -->
    <div class="bg-[#0d5c2f] rounded-2xl shadow-xl overflow-hidden mb-8">
        <div class="px-8 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-white/5 rounded-tr-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="mr-6 hidden md:block">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center border-2 border-white/30 shadow-lg">
                            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-receipt mr-3"></i>
                            Cash Inflow Details
                        </h2>
                        <p class="text-white/90 text-base">Reference: {{ $manual_cash_inflow->reference_no }}</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>View complete transaction information</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end text-white">
                    <div class="text-4xl font-bold mb-1">₱{{ number_format($manual_cash_inflow->amount, 2) }}</div>
                    <div class="text-sm opacity-90 mb-4">Transaction Amount</div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.manual-cash-inflows.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to List
                        </a>
                        @if($manual_cash_inflow->isPending())
                            <a href="{{ route('admin.manual-cash-inflows.edit', ['manual_cash_inflow' => $manual_cash_inflow->id]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Status</p>
                    <p class="text-xl font-bold {{ $manual_cash_inflow->isApproved() ? 'text-green-600' : ($manual_cash_inflow->isRejected() ? 'text-red-600' : 'text-yellow-600') }}">
                        {{ $manual_cash_inflow->isApproved() ? 'Approved' : ($manual_cash_inflow->isRejected() ? 'Rejected' : 'Pending') }}
                    </p>
                </div>
                <div class="w-12 h-12 {{ $manual_cash_inflow->isApproved() ? 'bg-green-500' : ($manual_cash_inflow->isRejected() ? 'bg-red-500' : 'bg-yellow-500') }} rounded-xl flex items-center justify-center shadow-lg">
                    @if($manual_cash_inflow->isApproved())
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    @elseif($manual_cash_inflow->isRejected())
                        <i class="fas fa-times-circle text-white text-lg"></i>
                    @else
                        <i class="fas fa-clock text-white text-lg"></i>
                    @endif
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    @if($manual_cash_inflow->isApproved())
                        <i class="fas fa-check text-green-500 mr-1"></i>
                        <span>Approved on {{ $manual_cash_inflow->approved_at->format('M d, Y') }}</span>
                    @elseif($manual_cash_inflow->isRejected())
                        <i class="fas fa-times text-red-500 mr-1"></i>
                        <span>Rejected on {{ $manual_cash_inflow->updated_at->format('M d, Y') }}</span>
                    @else
                        <i class="fas fa-clock text-yellow-500 mr-1"></i>
                        <span>Awaiting approval</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Amount Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Amount</p>
                    <p class="text-2xl font-bold text-green-600">₱{{ number_format($manual_cash_inflow->amount, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-peso-sign text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-down text-green-500 mr-1"></i>
                    <span>Cash inflow amount</span>
                </div>
            </div>
        </div>

        <!-- Source Type Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Source Type</p>
                    <p class="text-xl font-bold text-gray-900">{{ $manual_cash_inflow->getSourceTypeLabel() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-tag text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-info-circle text-purple-500 mr-1"></i>
                    <span>{{ $manual_cash_inflow->ministry ? $manual_cash_inflow->ministry->name : 'General' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                            <p class="text-sm font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $manual_cash_inflow->description }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Source Type</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $manual_cash_inflow->getSourceTypeBadgeClass() }}">
                                {{ $manual_cash_inflow->getSourceTypeLabel() }}
                            </span>
                        </div>
                        @if($manual_cash_inflow->source_details)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Source Details</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $manual_cash_inflow->source_details }}</p>
                            </div>
                        @endif
                        @if($manual_cash_inflow->source_type === 'other' && $manual_cash_inflow->other_source_specify)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Other Source Type</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $manual_cash_inflow->other_source_specify }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Ministry</label>
                            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $manual_cash_inflow->ministry ? $manual_cash_inflow->ministry->name : 'General Parish Fund' }}</p>
                        </div>
                    </div>
                    @if($manual_cash_inflow->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Notes</label>
                            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg whitespace-pre-line">{{ $manual_cash_inflow->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Approval Actions (if pending) -->
            @if($manual_cash_inflow->isPending())
                <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-gavel mr-2 text-[#0d5c2f]"></i>
                            Approval Actions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Approve Button -->
                            <form action="{{ route('admin.manual-cash-inflows.approve', ['manual_cash_inflow' => $manual_cash_inflow->id]) }}" method="POST">
                                @csrf
                                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                    <label class="block text-sm font-medium text-green-800 mb-2">Approve Cash Inflow</label>
                                    <p class="text-sm text-green-700 mb-4">This will add ₱{{ number_format($manual_cash_inflow->amount, 2) }} to the budget.</p>
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                        <i class="fas fa-check mr-2"></i>
                                        Approve & Add to Budget
                                    </button>
                                </div>
                            </form>

                            <!-- Reject Button -->
                            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                <label class="block text-sm font-medium text-red-800 mb-2">Reject Cash Inflow</label>
                                <p class="text-sm text-red-700 mb-4">Provide a reason for rejection.</p>
                                <button type="button" 
                                        onclick="openRejectModal()"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Metadata -->
        <div class="space-y-6">
            <!-- Transaction Details -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-receipt mr-2 text-[#0d5c2f]"></i>
                        Transaction Details
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Reference Number</label>
                        <p class="text-sm font-medium text-gray-900 font-mono bg-gray-50 p-2 rounded-lg">{{ $manual_cash_inflow->reference_no }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Created Date</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded-lg">{{ $manual_cash_inflow->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $manual_cash_inflow->created_at->format('h:i A') }}</p>
                    </div>
                    @if($manual_cash_inflow->isApproved())
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Approved Date</label>
                            <p class="text-sm text-gray-900 bg-green-50 p-2 rounded-lg">{{ $manual_cash_inflow->approved_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $manual_cash_inflow->approved_at->format('h:i A') }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Last Updated</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded-lg">{{ $manual_cash_inflow->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $manual_cash_inflow->updated_at->format('h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>
                        User Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Entered By</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded-lg">{{ $manual_cash_inflow->enteredBy->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $manual_cash_inflow->enteredBy->email }}</p>
                    </div>
                    @if($manual_cash_inflow->approvedBy)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Approved By</label>
                            <p class="text-sm text-gray-900 bg-green-50 p-2 rounded-lg">{{ $manual_cash_inflow->approvedBy->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $manual_cash_inflow->approvedBy->email }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Cash Inflow</h3>
            <form action="{{ route('admin.manual-cash-inflows.reject', ['manual_cash_inflow' => $manual_cash_inflow->id]) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" 
                              id="rejection_reason" 
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                              placeholder="Please provide a reason for rejecting this cash inflow"
                              required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection 