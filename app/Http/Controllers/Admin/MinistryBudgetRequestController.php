<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryBudgetRequest;
use App\Models\MinistryFundTransaction;
use Illuminate\Http\Request;

class MinistryBudgetRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = MinistryBudgetRequest::with(['ministry', 'requestedBy', 'approvedBy', 'activity']);

        // Filter by ministry
        if ($request->filled('ministry_id')) {
            $query->where('ministry_id', $request->ministry_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $requests = $query->latest()->paginate(20);

        // Get counts for all statuses (unfiltered)
        $allRequests = MinistryBudgetRequest::all();
        $statusCounts = [
            'pending' => $allRequests->where('status', 'pending')->count(),
            'approved' => $allRequests->where('status', 'approved')->count(),
            'rejected' => $allRequests->where('status', 'rejected')->count(),
            'completed' => $allRequests->where('status', 'completed')->count(),
        ];

        // Get ministries for filter dropdown
        $ministries = Ministry::orderBy('name')->get();

        // Check if this is being accessed from priest routes or if user is a priest
        if (str_starts_with(request()->route()->getName(), 'priest.') || (auth()->user() && auth()->user()->role === 'priest')) {
            return view('priest.ministries.ministry-activities.index', compact('requests', 'statusCounts', 'ministries'));
        }

        return view('admin.ministries.ministry-activities.index', compact('requests', 'statusCounts', 'ministries'));
    }

    public function create(Ministry $ministry)
    {
        return view('admin.ministries.ministry-activities.create', compact('ministry'));
    }

    public function store(Request $request, Ministry $ministry)
    {
        $data = $request->validate([
            'purpose' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        MinistryBudgetRequest::create([
            'ministry_id' => $ministry->id,
            'purpose' => $data['purpose'],
            'details' => $data['details'] ?? null,
            'status' => 'pending',
            'requested_by_user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.ministries.ministry-activities.index')->with('success', 'Ministry activity submitted.');
    }

    public function approve(MinistryBudgetRequest $requestModel)
    {
        if ($requestModel->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $requestModel->update([
            'status' => 'approved',
            'approved_by_user_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        MinistryFundTransaction::create([
            'ministry_id' => $requestModel->ministry_id,
            'type' => MinistryFundTransaction::TYPE_DEBIT,
            'amount' => $requestModel->amount, // This will now use the accessor from the activity
            'description' => 'Budget approved: ' . $requestModel->purpose,
            'source_type' => get_class($requestModel),
            'source_id' => $requestModel->id,
            'entered_by_user_id' => auth()->id(),
            'approved_by_user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Ministry activity approved and funds allocated.');
    }

    public function reject(MinistryBudgetRequest $requestModel, Request $request)
    {
        if ($requestModel->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $request->validate([
            'rejection_notes' => 'required|string|max:1000',
        ]);

        $requestModel->update([
            'status' => 'rejected',
            'approved_by_user_id' => auth()->id(),
            'approved_at' => now(),
            'rejection_notes' => $request->rejection_notes,
        ]);

        return back()->with('success', 'Ministry activity rejected.');
    }

    public function show(MinistryBudgetRequest $requestModel)
    {
        $requestModel->load(['ministry', 'activity', 'requestedBy', 'approvedBy', 'files.uploader']);
        
        // Calculate ministry budget stats
        $ministry = $requestModel->ministry;
        
        // Get total budget allocated to this ministry (from ministry fund transactions)
        $totalBudget = MinistryFundTransaction::where('ministry_id', $ministry->id)
            ->where('type', 'credit')
            ->sum('amount');
        
        // Calculate budget used (sum of all approved budget requests for this ministry)
        $budgetUsed = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->where('status', 'approved')
            ->with('activity')
            ->get()
            ->sum('amount'); // This will use the accessor that gets amount from activity
        
        // Get approved and pending requests count
        $approvedRequestsCount = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->where('status', 'approved')
            ->count();
            
        $pendingRequestsCount = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->where('status', 'pending')
            ->count();
        
        // Calculate remaining budget
        $remainingBudget = $totalBudget - $budgetUsed;
        
        // Add the calculated stats to the ministry object
        $ministry->budget = $totalBudget;
        $ministry->budget_used = $budgetUsed;
        $ministry->budget_remaining = $remainingBudget;
        $ministry->approved_requests_count = $approvedRequestsCount;
        $ministry->pending_requests_count = $pendingRequestsCount;
        
        // Calculate budget percentage for display
        $ministry->budget_percentage = $totalBudget > 0 ? min(100, ($budgetUsed / $totalBudget) * 100) : 0;
        
        // Check if this is being accessed from priest routes or if user is a priest
        if (str_starts_with(request()->route()->getName(), 'priest.') || (auth()->user() && auth()->user()->role === 'priest')) {
            return view('priest.ministries.ministry-activities.show', compact('requestModel'));
        }
        
        return view('admin.ministries.ministry-activities.show', compact('requestModel'));
    }
}


