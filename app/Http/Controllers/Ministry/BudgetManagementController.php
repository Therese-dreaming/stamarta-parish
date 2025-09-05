<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ministry;
use App\Models\MinistryFundTransaction;
use App\Models\MinistryBudgetRequest;
use App\Models\MinistryActivity;
use Carbon\Carbon;

class BudgetManagementController extends Controller
{
    public function index(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        // Get date range for filtering
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();
        
        // Get ministry fund transactions
        $transactions = MinistryFundTransaction::where('ministry_id', $ministry->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate budget statistics
        $totalBudget = MinistryFundTransaction::where('ministry_id', $ministry->id)
            ->where('type', 'credit')
            ->sum('amount');
        
        $totalExpenses = MinistryFundTransaction::where('ministry_id', $ministry->id)
            ->where('type', 'debit')
            ->sum('amount');
        
        $remainingBudget = $totalBudget - $totalExpenses;
        
        // Get budget requests statistics
        $pendingRequests = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->where('status', 'pending')
            ->count();
        
        $approvedRequests = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->where('status', 'approved')
            ->count();
        
        $rejectedRequests = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->where('status', 'rejected')
            ->count();
        
        // Get recent activities
        $recentActivities = $ministry->activities()
            ->with(['pendingBudgetRequest', 'approvedBudgetRequest'])
            ->orderBy('start_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('ministry.budget-management.index', compact(
            'ministry',
            'transactions',
            'totalBudget',
            'totalExpenses',
            'remainingBudget',
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests',
            'recentActivities',
            'startDate',
            'endDate'
        ));
    }
    
    public function show(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        
        // Get detailed budget timeline
        $timeline = $this->getBudgetTimeline($ministry);
        
        // Get budget requests
        $budgetRequests = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->with(['activity', 'requestedBy', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get activities with budget information
        $activities = $ministry->activities()
            ->with(['pendingBudgetRequest', 'approvedBudgetRequest'])
            ->orderBy('start_at', 'desc')
            ->get();
        
        return view('ministry.budget-management.show', compact(
            'ministry',
            'timeline',
            'budgetRequests',
            'activities'
        ));
    }
    
    private function getBudgetTimeline($ministry)
    {
        $timeline = collect();
        
        // Get ministry fund transactions
        $transactions = MinistryFundTransaction::where('ministry_id', $ministry->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach ($transactions as $transaction) {
            $timeline->push([
                'date' => $transaction->created_at,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'source_type' => 'Ministry Fund Transaction',
                'source_id' => $transaction->id,
                'color' => $transaction->type === 'credit' ? 'green' : 'red'
            ]);
        }
        
        // Get budget requests
        $budgetRequests = MinistryBudgetRequest::where('ministry_id', $ministry->id)
            ->with('activity')
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach ($budgetRequests as $request) {
            $timeline->push([
                'date' => $request->created_at,
                'type' => 'budget_request',
                'amount' => $request->amount,
                'description' => 'Budget Request: ' . ($request->activity ? $request->activity->title : 'Unknown Activity'),
                'source_type' => 'Budget Request',
                'source_id' => $request->id,
                'status' => $request->status,
                'color' => match($request->status) {
                    'approved' => 'green',
                    'pending' => 'yellow',
                    'rejected' => 'red',
                    default => 'gray'
                }
            ]);
        }
        
        return $timeline->sortByDesc('date');
    }
    
    private function getHeadMinistryOrAbort()
    {
        $user = auth()->user();
        
        if (!$user || $user->role !== 'ministry_head') {
            abort(403, 'Access denied. Ministry head role required.');
        }
        
        $ministry = Ministry::where('head_user_id', $user->id)->first();
        
        if (!$ministry) {
            abort(404, 'Ministry not found or you are not assigned as ministry head.');
        }
        
        return $ministry;
    }
} 
