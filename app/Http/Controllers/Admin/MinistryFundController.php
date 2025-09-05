<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryFundTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MinistryFundController extends Controller
{
    public function index(Ministry $ministry, Request $request)
    {
        $this->authorize('access-ministry');

        // Get paginated transactions with relationships
        $transactions = $ministry->transactions()
            ->with(['enteredBy', 'approvedBy', 'source'])
            ->latest()
            ->paginate(20);

        // Calculate current balance
        $balance = $ministry->transactions()
            ->selectRaw("SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END) as balance")
            ->value('balance') ?? 0;

        // Get total credits and debits
        $totalCredits = $ministry->transactions()->where('type', 'credit')->sum('amount');
        $totalDebits = $ministry->transactions()->where('type', 'debit')->sum('amount');

        // Get monthly balance trend data (last 12 months)
        $monthlyTrends = $this->getMonthlyBalanceTrends($ministry);

        // Get transaction distribution data
        $transactionDistribution = $this->getTransactionDistribution($ministry);

        // Get recent activity summary
        $recentActivity = $this->getRecentActivity($ministry);

        return view('admin.ministries.fund-overview', compact(
            'ministry', 
            'transactions', 
            'balance', 
            'totalCredits', 
            'totalDebits',
            'monthlyTrends',
            'transactionDistribution',
            'recentActivity'
        ));
    }

    /**
     * Get monthly balance trends for the last 12 months
     */
    private function getMonthlyBalanceTrends(Ministry $ministry)
    {
        $trends = [];
        $runningBalance = 0;

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            // Get transactions for this month
            $monthTransactions = $ministry->transactions()
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->get();

            $monthCredits = $monthTransactions->where('type', 'credit')->sum('amount');
            $monthDebits = $monthTransactions->where('type', 'debit')->sum('amount');
            $monthNet = $monthCredits - $monthDebits;

            $runningBalance += $monthNet;

            $trends[] = [
                'month' => $date->format('M Y'),
                'credits' => $monthCredits,
                'debits' => $monthDebits,
                'net' => $monthNet,
                'balance' => $runningBalance
            ];
        }

        return $trends;
    }

    /**
     * Get transaction distribution data
     */
    private function getTransactionDistribution(Ministry $ministry)
    {
        $distribution = $ministry->transactions()
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total_amount'))
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        return [
            'credits' => [
                'count' => $distribution->get('credit')->count ?? 0,
                'amount' => $distribution->get('credit')->total_amount ?? 0
            ],
            'debits' => [
                'count' => $distribution->get('debit')->count ?? 0,
                'amount' => $distribution->get('debit')->total_amount ?? 0
            ]
        ];
    }

    /**
     * Get recent activity summary
     */
    private function getRecentActivity(Ministry $ministry)
    {
        $lastMonth = Carbon::now()->subMonth();
        
        return [
            'last_month_transactions' => $ministry->transactions()
                ->where('created_at', '>=', $lastMonth)
                ->count(),
            'last_month_credits' => $ministry->transactions()
                ->where('type', 'credit')
                ->where('created_at', '>=', $lastMonth)
                ->sum('amount'),
            'last_month_debits' => $ministry->transactions()
                ->where('type', 'debit')
                ->where('created_at', '>=', $lastMonth)
                ->sum('amount'),
            'avg_transaction_amount' => $ministry->transactions()->avg('amount') ?? 0
        ];
    }
}


