<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MinistryFundTransaction;
use App\Models\MinistryBudgetRequest;
use App\Models\Booking;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BudgetManagementController extends Controller
{
    public function index(Request $request)
    {
        // Get all approved budget requests (outflows)
        $approvedBudgetRequests = MinistryBudgetRequest::with(['ministry', 'activity', 'approvedBy'])
            ->where('status', 'approved')
            ->latest('approved_at')
            ->paginate(20);

        // Get all cash inflows (bookings + manual entries)
        $cashInflows = MinistryFundTransaction::with(['ministry', 'enteredBy', 'source'])
            ->where('type', 'credit')
            ->latest()
            ->paginate(20);

        // Get all cash outflows (approved budget requests)
        $cashOutflows = MinistryFundTransaction::with(['ministry', 'enteredBy', 'source'])
            ->where('type', 'debit')
            ->latest()
            ->paginate(20);

        // Get parish total budget from parish_settings (this is the main current balance)
        $currentBalance = \App\Services\ParishBudgetService::getParishTotalBudget();
        
        // Calculate total inflows (all sources that add to parish budget)
        $manualCashInflows = \App\Models\ManualCashInflow::where('status', 'approved')->sum('amount');
        $bookingPayments = \App\Models\BookingPayment::where('payment_status', 'verified')->sum('total_fee');
        $totalInflows = $manualCashInflows + $bookingPayments;
        
        // Calculate total outflows (approved ministry budget requests)
        $totalOutflows = MinistryFundTransaction::where('type', 'debit')->sum('amount');
        
        // Ministry budgets (separate tracking for individual ministries)
        $totalMinistryBudgets = Ministry::sum('budget');

        // Get budget balance timeline
        $budgetTimeline = $this->getBudgetTimeline();

        // Get ministry-wise summary
        $ministrySummary = $this->getMinistrySummary();

        // Get recent activity
        $recentActivity = $this->getRecentActivity();

        return view('admin.budget-management.index', compact(
            'approvedBudgetRequests',
            'cashInflows',
            'cashOutflows',
            'totalInflows',
            'totalOutflows',
            'currentBalance',
            'budgetTimeline',
            'ministrySummary',
            'recentActivity',
            'bookingPayments'
        ));
    }

    /**
     * Get budget balance timeline
     */
    private function getBudgetTimeline()
    {
        $timeline = [];
        $runningBalance = 0;

        // Get all ministry fund transactions
        $transactions = MinistryFundTransaction::with(['ministry', 'enteredBy', 'approvedBy', 'source'])
            ->orderBy('created_at')
            ->get();

        $allTransactions = collect();

        // Add ministry fund transactions
        foreach ($transactions as $transaction) {
            $allTransactions->push([
                'type' => 'ministry_fund_transaction',
                'data' => $transaction,
                'date' => $transaction->created_at,
                'amount' => $transaction->amount,
                'transaction_type' => $transaction->type,
                'description' => $transaction->description,
                'ministry' => $transaction->ministry ? $transaction->ministry->name : 'General',
                'source_type' => $this->getSourceTypeLabel($transaction->source_type),
                'source_id' => $transaction->source_id
            ]);
        }

        // Add booking payments (these are separate from ministry fund transactions)
        $bookingPayments = \App\Models\BookingPayment::with(['booking.service'])
            ->where('payment_status', 'verified')
            ->orderBy('created_at')
            ->get();

        foreach ($bookingPayments as $payment) {
            $allTransactions->push([
                'type' => 'booking_payment',
                'data' => $payment,
                'date' => $payment->created_at,
                'amount' => $payment->total_fee,
                'transaction_type' => 'credit',
                'description' => 'Booking Payment: ' . ($payment->booking->service->name ?? 'Service'),
                'ministry' => 'General',
                'source_type' => 'BookingPayment',
                'source_id' => $payment->id
            ]);
        }

        // Sort by date
        $allTransactions = $allTransactions->sortBy('date');

        foreach ($allTransactions as $item) {
            $balanceBefore = $runningBalance;
            
            if ($item['transaction_type'] === 'credit') {
                $runningBalance += $item['amount'];
            } else {
                $runningBalance -= $item['amount'];
            }

            $balanceAfter = $runningBalance;

            $timeline[] = [
                'date' => $item['date'],
                'type' => $item['transaction_type'],
                'amount' => $item['amount'],
                'description' => $item['description'],
                'ministry' => $item['ministry'],
                'source_type' => $item['source_type'],
                'source_id' => $item['source_id'],
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'transaction' => $item['data'],
                'is_booking_payment' => $item['type'] === 'booking_payment'
            ];
        }

        return $timeline;
    }

    /**
     * Get ministry-wise budget summary
     */
    private function getMinistrySummary()
    {
        $ministries = Ministry::with(['transactions', 'budgetRequests'])->get();
        
        $summary = [];
        
        foreach ($ministries as $ministry) {
            $inflows = $ministry->transactions()->where('type', 'credit')->sum('amount');
            $outflows = $ministry->transactions()->where('type', 'debit')->sum('amount');
            
            // Use the actual ministry budget column for balance
            $balance = $ministry->budget ?? 0;
            
            $approvedRequests = $ministry->budgetRequests()->where('status', 'approved')->count();
            $pendingRequests = $ministry->budgetRequests()->where('status', 'pending')->count();
            
            $summary[] = [
                'ministry' => $ministry,
                'inflows' => $inflows,
                'outflows' => $outflows,
                'balance' => $balance,
                'approved_requests' => $approvedRequests,
                'pending_requests' => $pendingRequests
            ];
        }

        return $summary;
    }

    /**
     * Get recent budget activity
     */
    private function getRecentActivity()
    {
        $lastMonth = Carbon::now()->subMonth();
        
        return [
            'recent_approvals' => MinistryBudgetRequest::where('status', 'approved')
                ->where('approved_at', '>=', $lastMonth)
                ->count(),
            'recent_inflows' => MinistryFundTransaction::where('type', 'credit')
                ->where('created_at', '>=', $lastMonth)
                ->sum('amount'),
            'recent_outflows' => MinistryFundTransaction::where('type', 'debit')
                ->where('created_at', '>=', $lastMonth)
                ->sum('amount'),
            'total_transactions' => MinistryFundTransaction::where('created_at', '>=', $lastMonth)->count()
        ];
    }

    /**
     * Show detailed view of a specific transaction
     */
    public function show(MinistryFundTransaction $transaction)
    {
        // Get balance before this transaction
        $balanceBefore = MinistryFundTransaction::where('created_at', '<', $transaction->created_at)
            ->selectRaw("SUM(CASE WHEN type = 'credit' THEN amount ELSE -amount END) as balance")
            ->value('balance') ?? 0;

        $balanceAfter = $balanceBefore + ($transaction->type === 'credit' ? $transaction->amount : -$transaction->amount);

        return view('admin.budget-management.show', compact('transaction', 'balanceBefore', 'balanceAfter'));
    }

    /**
     * Helper to get source type label
     */
    private function getSourceTypeLabel($sourceType)
    {
        switch ($sourceType) {
            case \App\Models\MinistryBudgetRequest::class:
                return 'Budget Request';
            case \App\Models\ManualCashInflow::class:
                return 'Manual Cash Inflow';
            case \App\Models\BookingPayment::class:
                return 'Booking Payment';
            default:
                return 'Other Transaction';
        }
    }
}
