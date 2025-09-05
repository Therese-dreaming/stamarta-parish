@extends('layouts.admin')

@section('title', 'Cash Inflow Details')

@section('content')
@include('components.toast')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <!-- Header / Actions -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-2xl font-bold text-gray-900">Manual Cash Inflow</h2>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $manual_cash_inflow->getStatusBadgeClass() }}">
                                {{ ucfirst($manual_cash_inflow->status) }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">Reference: <span class="font-mono text-gray-900">{{ $manual_cash_inflow->reference_no ?? 'N/A' }}</span></div>
                        <div class="text-xs text-gray-500 mt-1">Created: {{ optional($manual_cash_inflow->created_at)->format('M d, Y h:i A') ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.manual-cash-inflows.index') }}" class="px-3 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 text-xs">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    @if($manual_cash_inflow->isPending())
                        <button type="button" onclick="openApproveModal()" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                        <button type="button" onclick="openRejectModal()" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                        <a href="{{ route('admin.manual-cash-inflows.edit', ['manual_cash_inflow' => $manual_cash_inflow->id]) }}" class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    @endif
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
                    <p class="text-xs text-gray-500 mt-1">
                        @if($manual_cash_inflow->isApproved())
                            Approved on {{ optional($manual_cash_inflow->approved_at)->format('M d, Y') ?? 'N/A' }}
                        @elseif($manual_cash_inflow->isRejected())
                            Updated on {{ optional($manual_cash_inflow->updated_at)->format('M d, Y') ?? 'N/A' }}
                        @else
                            Awaiting approval
                        @endif
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

            <!-- Approval Actions (Toolbar replicated above) -->
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
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <label class="block text-sm font-medium text-green-800 mb-2">Approve Cash Inflow</label>
                                <p class="text-sm text-green-700 mb-4">This will add ₱{{ number_format($manual_cash_inflow->amount, 2) }} to the budget.</p>
                                <button type="button" onclick="openApproveModal()" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Approve & Add to Budget
                                </button>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                <label class="block text-sm font-medium text-red-800 mb-2">Reject Cash Inflow</label>
                                <p class="text-sm text-red-700 mb-4">Provide a reason for rejection.</p>
                                <button type="button" onclick="openRejectModal()" class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
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
                        <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded-lg">{{ optional($manual_cash_inflow->created_at)->format('M d, Y') ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ optional($manual_cash_inflow->created_at)->format('h:i A') ?? 'N/A' }}</p>
                    </div>
                    @if($manual_cash_inflow->isApproved())
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Approved Date</label>
                            <p class="text-sm text-gray-900 bg-green-50 p-2 rounded-lg">{{ optional($manual_cash_inflow->approved_at)->format('M d, Y') ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ optional($manual_cash_inflow->approved_at)->format('h:i A') ?? 'N/A' }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Last Updated</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded-lg">{{ optional($manual_cash_inflow->updated_at)->format('M d, Y') ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ optional($manual_cash_inflow->updated_at)->format('h:i A') ?? 'N/A' }}</p>
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

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-24 mx-auto p-5 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Confirm Approval</h3>
                <button onclick="closeApproveModal()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('admin.manual-cash-inflows.approve', ['manual_cash_inflow' => $manual_cash_inflow->id]) }}" method="POST">
                @csrf
                <div class="px-5 py-4">
                    <p class="text-sm text-gray-600">Approve this cash inflow and add <span class="font-semibold text-gray-900">₱{{ number_format($manual_cash_inflow->amount, 2) }}</span> to the budget?</p>
                </div>
                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 text-xs rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-xs rounded-lg bg-green-600 hover:bg-green-700 text-white">Approve</button>
                </div>
            </form>
        </div>
    </div>
    </div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-24 mx-auto p-5 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Reject Cash Inflow</h3>
                <button onclick="closeRejectModal()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('admin.manual-cash-inflows.reject', ['manual_cash_inflow' => $manual_cash_inflow->id]) }}" method="POST">
                @csrf
                <div class="px-5 py-4 space-y-3">
                    <p class="text-sm text-gray-600">Provide a reason for rejection. This will be saved with the record.</p>
                    <textarea name="rejection_reason" id="rejection_reason" rows="5" class="w-full border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm" placeholder="Reason for rejection" required></textarea>
                </div>
                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-xs rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-xs rounded-lg bg-red-600 hover:bg-red-700 text-white">Reject</button>
                </div>
            </form>
        </div>
    </div>
    </div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-24 mx-auto p-5 w-full max-w-2xl">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Full Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <div class="px-5 py-4 space-y-4 text-sm">
                <div>
                    <div class="text-gray-500 mb-1">Description</div>
                    <div class="bg-gray-50 rounded-lg p-3 text-gray-900">{{ $manual_cash_inflow->description }}</div>
                </div>
                @if($manual_cash_inflow->source_details)
                <div>
                    <div class="text-gray-500 mb-1">Source Details</div>
                    <div class="bg-gray-50 rounded-lg p-3 text-gray-900">{{ $manual_cash_inflow->source_details }}</div>
                </div>
                @endif
                @if($manual_cash_inflow->notes)
                <div>
                    <div class="text-gray-500 mb-1">Notes</div>
                    <div class="bg-gray-50 rounded-lg p-3 text-gray-900 whitespace-pre-line">{{ $manual_cash_inflow->notes }}</div>
                </div>
                @endif
            </div>
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-end">
                <button type="button" onclick="closeDetailsModal()" class="px-4 py-2 text-xs rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Close</button>
            </div>
        </div>
    </div>
    </div>

<script>
function openRejectModal() { document.getElementById('rejectModal').classList.remove('hidden'); }
function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }
function openApproveModal() { document.getElementById('approveModal').classList.remove('hidden'); }
function closeApproveModal() { document.getElementById('approveModal').classList.add('hidden'); }
function openDetailsModal() { document.getElementById('detailsModal').classList.remove('hidden'); }
function closeDetailsModal() { document.getElementById('detailsModal').classList.add('hidden'); }

// Close modal when clicking outside
['rejectModal','approveModal','detailsModal'].forEach(function(id){
  const el = document.getElementById(id);
  if (el) {
    el.addEventListener('click', function(e){ if (e.target === this) { this.classList.add('hidden'); } });
  }
});
</script>
@endsection 