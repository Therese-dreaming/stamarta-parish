<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryBudgetRequest;
use App\Models\MinistryFundTransaction;
use Illuminate\Http\Request;

class MinistryBudgetRequestController extends Controller
{
    public function index()
    {
        $requests = MinistryBudgetRequest::with(['ministry', 'requestedBy', 'approvedBy'])
            ->latest()
            ->paginate(20);
        return view('admin.ministries.budget-requests.index', compact('requests'));
    }

    public function create(Ministry $ministry)
    {
        return view('admin.ministries.budget-requests.create', compact('ministry'));
    }

    public function store(Request $request, Ministry $ministry)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'purpose' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        MinistryBudgetRequest::create([
            'ministry_id' => $ministry->id,
            'amount' => $data['amount'],
            'purpose' => $data['purpose'],
            'details' => $data['details'] ?? null,
            'status' => 'pending',
            'requested_by_user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.ministries.budget-requests.index')->with('success', 'Budget request submitted.');
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
            'type' => MinistryFundTransaction::TYPE_CREDIT,
            'amount' => $requestModel->amount,
            'description' => 'Budget approved: ' . $requestModel->purpose,
            'source_type' => get_class($requestModel),
            'source_id' => $requestModel->id,
            'entered_by_user_id' => auth()->id(),
            'approved_by_user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Budget request approved and funds credited.');
    }

    public function reject(MinistryBudgetRequest $requestModel, Request $request)
    {
        if ($requestModel->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $requestModel->update([
            'status' => 'rejected',
            'approved_by_user_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Budget request rejected.');
    }
}


