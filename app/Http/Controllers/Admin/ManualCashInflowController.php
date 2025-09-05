<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ministry;
use App\Models\ManualCashInflow;
use App\Models\MinistryFundTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ManualCashInflowController extends Controller
{
    public function index(Request $request)
    {
        // Admin view: show all inflows with filters
        $this->ensureAdmin();

        $query = ManualCashInflow::with(['enteredBy', 'approvedBy', 'ministry']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source_type')) {
            $query->where('source_type', $request->source_type);
        }

        if ($request->filled('ministry_id')) {
            $query->where('ministry_id', $request->ministry_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $cashInflows = $query->orderByDesc('created_at')->paginate(20);
        $ministries = Ministry::orderBy('name')->get();

        return view('admin.manual-cash-inflows.index', compact('cashInflows', 'ministries'));
    }
    
    public function create()
    {
        $this->ensureAdmin();
        $ministries = Ministry::orderBy('name')->get();
        return view('admin.manual-cash-inflows.create', compact('ministries'));
    }
    
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'source_type' => 'required|string|in:diocese,donation,fundraising,event_revenue,membership_fee,sponsorship,other',
            'description' => 'required|string|max:500',
            'source_details' => 'nullable|string|max:500',
            'other_source_specify' => 'required_if:source_type,other|nullable|string|max:100',
            'reference_no' => 'nullable|string|max:50|unique:manual_cash_inflows,reference_no',
            'notes' => 'nullable|string|max:1000',
            'ministry_id' => 'nullable|exists:ministries,id',
        ]);

        try {
            $referenceNo = $request->reference_no ?: 'MCI-' . strtoupper(uniqid());

            ManualCashInflow::create([
                'ministry_id' => $request->ministry_id,
                'amount' => $request->amount,
                'source_type' => $request->source_type,
                'description' => $request->description,
                'source_details' => $request->source_details,
                'other_source_specify' => $request->other_source_specify,
                'reference_no' => $referenceNo,
                'notes' => $request->notes,
                'entered_by_user_id' => auth()->id(),
                'status' => ManualCashInflow::STATUS_PENDING,
            ]);

            return redirect()->route('admin.manual-cash-inflows.index')
                ->with('success', 'Cash inflow created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create manual cash inflow: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to submit cash inflow. Please try again.');
        }
    }
    
    public function show(ManualCashInflow $manual_cash_inflow)
    {
        $this->ensureAdmin();
        $manual_cash_inflow->load(['enteredBy', 'approvedBy', 'ministry']);
        return view('admin.manual-cash-inflows.show', compact('manual_cash_inflow'));
    }
    
    public function edit(ManualCashInflow $manual_cash_inflow)
    {
        $this->ensureAdmin();
        if (!$manual_cash_inflow->isPending()) {
            return redirect()->route('admin.manual-cash-inflows.show', ['manual_cash_inflow' => $manual_cash_inflow->id])
                ->with('error', 'Cannot edit cash inflow that has already been processed.');
        }
        $ministries = Ministry::orderBy('name')->get();
        return view('admin.manual-cash-inflows.edit', compact('manual_cash_inflow', 'ministries'));
    }
    
    public function update(Request $request, ManualCashInflow $manual_cash_inflow)
    {
        $this->ensureAdmin();

        if (!$manual_cash_inflow->isPending()) {
            return redirect()->route('admin.manual-cash-inflows.show', ['manual_cash_inflow' => $manual_cash_inflow->id])
                ->with('error', 'Cannot update cash inflow that has already been processed.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'source_type' => 'required|string|in:diocese,donation,fundraising,event_revenue,membership_fee,sponsorship,other',
            'description' => 'required|string|max:500',
            'source_details' => 'nullable|string|max:500',
            'other_source_specify' => 'required_if:source_type,other|nullable|string|max:100',
            'reference_no' => 'nullable|string|max:50|unique:manual_cash_inflows,reference_no,' . $manual_cash_inflow->id,
            'notes' => 'nullable|string|max:1000',
            'ministry_id' => 'nullable|exists:ministries,id',
        ]);

        try {
            $manual_cash_inflow->update([
                'ministry_id' => $request->ministry_id,
                'amount' => $request->amount,
                'source_type' => $request->source_type,
                'description' => $request->description,
                'source_details' => $request->source_details,
                'other_source_specify' => $request->other_source_specify,
                'reference_no' => $request->reference_no,
                'notes' => $request->notes,
            ]);

            return redirect()->route('admin.manual-cash-inflows.show', ['manual_cash_inflow' => $manual_cash_inflow->id])
                ->with('success', 'Cash inflow updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update manual cash inflow: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update cash inflow. Please try again.');
        }
    }
    
    public function destroy(ManualCashInflow $manual_cash_inflow)
    {
        $this->ensureAdmin();

        if (!$manual_cash_inflow->isPending()) {
            return redirect()->route('admin.manual-cash-inflows.show', ['manual_cash_inflow' => $manual_cash_inflow->id])
                ->with('error', 'Cannot delete cash inflow that has already been processed.');
        }

        try {
            $manual_cash_inflow->delete();

            return redirect()->route('admin.manual-cash-inflows.index')
                ->with('success', 'Cash inflow deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete manual cash inflow: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete cash inflow. Please try again.');
        }
    }
    
    public function approve(ManualCashInflow $manual_cash_inflow)
    {
        $this->ensureAdmin();

        if (!$manual_cash_inflow->canBeApproved()) {
            return back()->with('error', 'Cash inflow cannot be approved.');
        }

        try {
            DB::transaction(function () use ($manual_cash_inflow) {
                $manual_cash_inflow->update([
                    'status' => ManualCashInflow::STATUS_APPROVED,
                    'approved_by_user_id' => auth()->id(),
                    'approved_at' => Carbon::now(),
                ]);

                MinistryFundTransaction::create([
                    'ministry_id' => $manual_cash_inflow->ministry_id,
                    'type' => MinistryFundTransaction::TYPE_CREDIT,
                    'amount' => $manual_cash_inflow->amount,
                    'description' => 'Manual cash inflow approved: ' . ($manual_cash_inflow->description ?? ''),
                    'reference_no' => $manual_cash_inflow->reference_no,
                    'source_type' => ManualCashInflow::class,
                    'source_id' => $manual_cash_inflow->id,
                    'entered_by_user_id' => $manual_cash_inflow->entered_by_user_id,
                    'approved_by_user_id' => auth()->id(),
                ]);
            });

            return back()->with('success', 'Cash inflow approved and funds added.');
        } catch (\Exception $e) {
            Log::error('Failed to approve manual cash inflow: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve cash inflow.');
        }
    }

    public function reject(Request $request, ManualCashInflow $manual_cash_inflow)
    {
        $this->ensureAdmin();

        if (!$manual_cash_inflow->canBeRejected()) {
            return back()->with('error', 'Cash inflow cannot be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        try {
            $notes = trim(($manual_cash_inflow->notes ?? '') . "\nRejection reason: " . $request->rejection_reason);
            $manual_cash_inflow->update([
                'status' => ManualCashInflow::STATUS_REJECTED,
                'notes' => $notes,
            ]);

            return back()->with('success', 'Cash inflow rejected.');
        } catch (\Exception $e) {
            Log::error('Failed to reject manual cash inflow: ' . $e->getMessage());
            return back()->with('error', 'Failed to reject cash inflow.');
        }
    }

    private function ensureAdmin(): void
    {
        $user = auth()->user();
        if (!$user || ($user->role ?? null) !== 'admin') {
            abort(403, 'Access denied. Admin role required.');
        }
    }
}