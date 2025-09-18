<?php

namespace App\Http\Controllers\Ministry;

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
        $ministry = $this->getHeadMinistryOrAbort();
        
        $query = ManualCashInflow::where('ministry_id', $ministry->id)
            ->with(['enteredBy', 'approvedBy']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        $cashInflows = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Calculate statistics
        $totalAmount = $query->sum('amount');
        $pendingAmount = $query->where('status', 'pending')->sum('amount');
        $approvedAmount = $query->where('status', 'approved')->sum('amount');
        $rejectedAmount = $query->where('status', 'rejected')->sum('amount');
        
        // Get counts
        $totalCount = $query->count();
        $pendingCount = $query->where('status', 'pending')->count();
        $approvedCount = $query->where('status', 'approved')->count();
        $rejectedCount = $query->where('status', 'rejected')->count();
        
        return view('ministry.manual-cash-inflows.index', compact(
            'ministry',
            'cashInflows',
            'totalAmount',
            'pendingAmount',
            'approvedAmount',
            'rejectedAmount',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }
    
    public function create()
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        return view('ministry.manual-cash-inflows.create', compact('ministry'));
    }
    
    public function store(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'source_type' => 'required|string|in:diocese,donation,fundraising,event_revenue,membership_fee,sponsorship,other',
            'description' => 'required|string|max:500',
            'source_details' => 'nullable|string|max:500',
            'other_source_specify' => 'required_if:source_type,other|nullable|string|max:100',
            'reference_no' => 'nullable|string|max:50|unique:manual_cash_inflows,reference_no',
            'date_received' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        try {
            $referenceNo = $request->reference_no ?: 'MCI-' . strtoupper(uniqid());

            $cashInflow = ManualCashInflow::create([
                'ministry_id' => $ministry->id,
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
            
            return redirect()->route('ministry.manual-cash-inflows.index')
                ->with('success', 'Cash inflow request submitted successfully. Waiting for admin approval.');
                
        } catch (\Exception $e) {
            Log::error('Failed to create manual cash inflow: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to submit cash inflow request. Please try again.');
        }
    }
    
    public function show(ManualCashInflow $cashInflow)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        // Ensure the cash inflow belongs to this ministry or was created by this user
        if (!is_null($cashInflow->ministry_id) && !$this->userHeadsMinistryId($cashInflow->ministry_id)) {
            abort(403, 'Access denied. This cash inflow does not belong to your ministry.');
        }
        
        $cashInflow->load(['enteredBy', 'approvedBy']);
        
        return view('ministry.manual-cash-inflows.show', compact('ministry', 'cashInflow'));
    }
    
    public function edit(ManualCashInflow $cashInflow)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        // Ensure the cash inflow belongs to this ministry or was created by this user
        if (!is_null($cashInflow->ministry_id) && !$this->userHeadsMinistryId($cashInflow->ministry_id)) {
            abort(403, 'Access denied. This cash inflow does not belong to your ministry.');
        }
        
        // Only allow editing if status is pending
        if ($cashInflow->status !== 'pending') {
            return redirect()->route('ministry.manual-cash-inflows.show', $cashInflow)
                ->with('error', 'Cannot edit cash inflow that has already been processed.');
        }
        
        return view('ministry.manual-cash-inflows.edit', compact('ministry', 'cashInflow'));
    }
    
    public function update(Request $request, ManualCashInflow $cashInflow)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        // Ensure the cash inflow belongs to this ministry or was created by this user
        if (!is_null($cashInflow->ministry_id) && !$this->userHeadsMinistryId($cashInflow->ministry_id)) {
            abort(403, 'Access denied. This cash inflow does not belong to your ministry.');
        }
        
        // Only allow updating if status is pending
        if ($cashInflow->status !== 'pending') {
            return redirect()->route('ministry.manual-cash-inflows.show', $cashInflow)
                ->with('error', 'Cannot update cash inflow that has already been processed.');
        }
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'source_type' => 'required|string|in:diocese,donation,fundraising,event_revenue,membership_fee,sponsorship,other',
            'description' => 'required|string|max:500',
            'source_details' => 'nullable|string|max:500',
            'other_source_specify' => 'required_if:source_type,other|nullable|string|max:100',
            'reference_no' => 'nullable|string|max:50|unique:manual_cash_inflows,reference_no,' . $cashInflow->id,
            'date_received' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        try {
            $cashInflow->update([
                'amount' => $request->amount,
                'source_type' => $request->source_type,
                'description' => $request->description,
                'source_details' => $request->source_details,
                'other_source_specify' => $request->other_source_specify,
                'reference_no' => $request->reference_no,
                'notes' => $request->notes,
            ]);
            
            return redirect()->route('ministry.manual-cash-inflows.show', $cashInflow)
                ->with('success', 'Cash inflow updated successfully.');
                
        } catch (\Exception $e) {
            Log::error('Failed to update manual cash inflow: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update cash inflow. Please try again.');
        }
    }
    
    public function destroy(ManualCashInflow $cashInflow)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        // Ensure the cash inflow belongs to this ministry or was created by this user
        if (!is_null($cashInflow->ministry_id) && !$this->userHeadsMinistryId($cashInflow->ministry_id)) {
            abort(403, 'Access denied. This cash inflow does not belong to your ministry.');
        }
        
        // Only allow deletion if status is pending
        if ($cashInflow->status !== 'pending') {
            return redirect()->route('ministry.manual-cash-inflows.show', $cashInflow)
                ->with('error', 'Cannot delete cash inflow that has already been processed.');
        }
        
        try {
            $cashInflow->delete();
            
            return redirect()->route('ministry.manual-cash-inflows.index')
                ->with('success', 'Cash inflow deleted successfully.');
                
        } catch (\Exception $e) {
            Log::error('Failed to delete manual cash inflow: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete cash inflow. Please try again.');
        }
    }
    
    private function getHeadMinistryOrAbort()
    {
        $user = auth()->user();
        
        if (!$user || $user->role !== 'ministry_head') {
            abort(403, 'Access denied. Ministry head role required.');
        }
        
        // Allow selecting a specific ministry the head owns
        $requestedMinistryId = request()->get('ministry_id');
        if ($requestedMinistryId) {
            $selected = Ministry::where('id', $requestedMinistryId)
                ->where('head_user_id', $user->id)
                ->first();
            if ($selected) {
                return $selected;
            }
        }

        $ministry = Ministry::where('head_user_id', $user->id)->first();
        
        if (!$ministry) {
            abort(404, 'Ministry not found or you are not assigned as ministry head.');
        }
        
        return $ministry;
    }

    private function userHeadsMinistryId(?int $ministryId): bool
    {
        if (!$ministryId) {
            return false;
        }
        return Ministry::where('id', $ministryId)
            ->where('head_user_id', auth()->id())
            ->exists();
    }
} 
