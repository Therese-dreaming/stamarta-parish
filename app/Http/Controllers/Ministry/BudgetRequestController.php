<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryBudgetRequest;
use App\Models\MinistryBudgetRequestFile;
use Illuminate\Http\Request;

class BudgetRequestController extends Controller
{
    private function getHeadMinistryOrAbort(): Ministry
    {
        $user = auth()->user();
        $ministry = Ministry::where('head_user_id', $user->id)->first();
        if (!$ministry) {
            abort(403);
        }
        return $ministry;
    }

    public function index()
    {
        $ministry = $this->getHeadMinistryOrAbort();
        $requests = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->latest()
            ->paginate(20);
        return view('ministry.budget-requests.index', compact('ministry', 'requests'));
    }

    public function create()
    {
        $ministry = $this->getHeadMinistryOrAbort();
        return view('ministry.budget-requests.create', compact('ministry'));
    }

    public function store(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'purpose' => 'required|string|max:255',
            'details' => 'nullable|string',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:8192',
        ]);

        $req = MinistryBudgetRequest::create([
            'ministry_id' => $ministry->id,
            'amount' => $data['amount'],
            'purpose' => $data['purpose'],
            'details' => $data['details'] ?? null,
            'status' => 'pending',
            'requested_by_user_id' => auth()->id(),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $storedPath = $file->store('budget-requests/' . $ministry->id, 'public');
                MinistryBudgetRequestFile::create([
                    'budget_request_id' => $req->id,
                    'path' => $storedPath,
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('ministry.budget-requests.index')->with('success', 'Budget request submitted.');
    }
}


