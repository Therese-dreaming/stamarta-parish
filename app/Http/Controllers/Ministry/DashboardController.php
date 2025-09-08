<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryActivity;
use App\Models\MinistryMember;
use App\Models\MinistryBudgetRequest;
use App\Models\ManualCashInflow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ministry = Ministry::where('head_user_id', $user->id)
            ->withCount(['members', 'activities', 'budgetRequests', 'manualCashInflows'])
            ->first();

        if (!$ministry) {
            return view('ministry.dashboard', compact('ministry'));
        }

        // Get current year and month for filtering
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $startOfYear = Carbon::createFromDate($currentYear, 1, 1);
        $endOfYear = Carbon::createFromDate($currentYear, 12, 31);

        // Key metrics
        $totalMembers = $ministry->members_count;
        $totalActivities = $ministry->activities_count;
        $totalBudgetRequests = $ministry->budget_requests_count;
        $totalCashInflows = $ministry->manual_cash_inflows_count;

        // Calculate amounts
        $totalAmount = $this->getTotalAmount($ministry);
        $pendingAmount = $this->getPendingAmount($ministry);
        $approvedAmount = $this->getApprovedAmount($ministry);
        $rejectedAmount = $this->getRejectedAmount($ministry);

        // Chart data
        $activitiesData = $this->getActivitiesChartData($ministry, $currentYear);
        $budgetRequestsData = $this->getBudgetRequestsChartData($ministry, $currentYear);
        $memberGrowthData = $this->getMemberGrowthChartData($ministry, $currentYear);
        $cashInflowData = $this->getCashInflowChartData($ministry, $currentYear);
        $cashInflowSourceData = $this->getCashInflowSourceChartData($ministry);
        $activityCompletionData = $this->getActivityCompletionChartData($ministry, $currentYear);
        $budgetApprovalRateData = $this->getBudgetApprovalRateChartData($ministry, $currentYear);
        $memberRoleData = $this->getMemberRoleChartData($ministry);
        $budgetStatusData = $this->getBudgetStatusChartData($ministry);
        $memberStatusData = $this->getMemberStatusChartData($ministry);

        // Recent activities
        $recentActivities = $this->getRecentActivities($ministry);

        return view('ministry.dashboard', compact(
            'ministry',
            'totalMembers',
            'totalActivities',
            'totalBudgetRequests',
            'totalCashInflows',
            'totalAmount',
            'pendingAmount',
            'approvedAmount',
            'rejectedAmount',
            'activitiesData',
            'budgetRequestsData',
            'memberGrowthData',
            'cashInflowData',
            'cashInflowSourceData',
            'activityCompletionData',
            'budgetApprovalRateData',
            'memberRoleData',
            'budgetStatusData',
            'memberStatusData',
            'recentActivities'
        ));
    }

    private function getTotalAmount($ministry)
    {
        return $ministry->manualCashInflows()->sum('amount');
    }

    private function getPendingAmount($ministry)
    {
        return $ministry->manualCashInflows()->where('status', 'pending')->sum('amount');
    }

    private function getApprovedAmount($ministry)
    {
        return $ministry->manualCashInflows()->where('status', 'approved')->sum('amount');
    }

    private function getRejectedAmount($ministry)
    {
        return $ministry->manualCashInflows()->where('status', 'rejected')->sum('amount');
    }

    private function getActivitiesChartData($ministry, $year)
    {
        $activities = $ministry->activities()
            ->selectRaw('MONTH(start_at) as month, COUNT(*) as count')
            ->whereYear('start_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $activities[$i] ?? 0;
        }

        return [
            'labels' => $months,
            'data' => $data,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getBudgetRequestsChartData($ministry, $year)
    {
        $budgetRequests = $ministry->budgetRequests()
            ->join('ministry_activities', 'ministry_budget_requests.activity_id', '=', 'ministry_activities.id')
            ->selectRaw('MONTH(ministry_activities.start_at) as month, SUM(ministry_activities.estimated_budget) as total')
            ->whereYear('ministry_activities.start_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = (float)($budgetRequests[$i] ?? 0);
        }

        return [
            'labels' => $months,
            'data' => $data,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getMemberGrowthChartData($ministry, $year)
    {
        $members = $ministry->members()
            ->selectRaw('MONTH(joined_at) as month, COUNT(*) as count')
            ->whereYear('joined_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $members[$i] ?? 0;
        }

        return [
            'labels' => $months,
            'data' => $data,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getCashInflowChartData($ministry, $year)
    {
        $cashInflows = $ministry->manualCashInflows()
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = (float)($cashInflows[$i] ?? 0);
        }

        return [
            'labels' => $months,
            'data' => $data,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getCashInflowSourceChartData($ministry)
    {
        $sources = $ministry->manualCashInflows()
            ->selectRaw('source_type, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('source_type')
            ->get()
            ->keyBy('source_type');

        $labels = ['Diocese', 'Donation', 'Fundraising', 'Event Revenue', 'Membership Fee', 'Sponsorship', 'Other'];
        $data = [];
        $colors = ['#0d5c2f', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#6b7280'];

        $sourceMapping = [
            'diocese' => 'Diocese',
            'donation' => 'Donation',
            'fundraising' => 'Fundraising',
            'event_revenue' => 'Event Revenue',
            'membership_fee' => 'Membership Fee',
            'sponsorship' => 'Sponsorship',
            'other' => 'Other'
        ];

        foreach ($labels as $label) {
            $sourceKey = array_search($label, $sourceMapping);
            $sourceData = $sources->get($sourceKey);
            $data[] = $sourceData ? (float)$sourceData->total : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getActivityCompletionChartData($ministry, $year)
    {
        $activities = $ministry->activities()
            ->selectRaw('
                MONTH(start_at) as month,
                SUM(CASE WHEN start_at < NOW() THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN start_at >= NOW() THEN 1 ELSE 0 END) as upcoming
            ')
            ->whereYear('start_at', $year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $completedData = [];
        $upcomingData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthData = $activities->get($i);
            $completedData[] = $monthData ? (int)$monthData->completed : 0;
            $upcomingData[] = $monthData ? (int)$monthData->upcoming : 0;
        }

        $totalCompleted = array_sum($completedData);
        $totalUpcoming = array_sum($upcomingData);
        
        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Completed',
                    'data' => $completedData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => '#10b981'
                ],
                [
                    'label' => 'Upcoming',
                    'data' => $upcomingData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => '#3b82f6'
                ]
            ],
            'isEmpty' => ($totalCompleted + $totalUpcoming) === 0
        ];
    }

    private function getBudgetApprovalRateChartData($ministry, $year)
    {
        $budgetRequests = $ministry->budgetRequests()
            ->join('ministry_activities', 'ministry_budget_requests.activity_id', '=', 'ministry_activities.id')
            ->selectRaw('
                MONTH(ministry_activities.start_at) as month,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected
            ')
            ->whereYear('ministry_activities.start_at', $year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $approvedData = [];
        $pendingData = [];
        $rejectedData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthData = $budgetRequests->get($i);
            $approvedData[] = $monthData ? (int)$monthData->approved : 0;
            $pendingData[] = $monthData ? (int)$monthData->pending : 0;
            $rejectedData[] = $monthData ? (int)$monthData->rejected : 0;
        }

        $totalApproved = array_sum($approvedData);
        $totalPending = array_sum($pendingData);
        $totalRejected = array_sum($rejectedData);
        
        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Approved',
                    'data' => $approvedData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => '#10b981'
                ],
                [
                    'label' => 'Pending',
                    'data' => $pendingData,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.8)',
                    'borderColor' => '#f59e0b'
                ],
                [
                    'label' => 'Rejected',
                    'data' => $rejectedData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => '#ef4444'
                ]
            ],
            'isEmpty' => ($totalApproved + $totalPending + $totalRejected) === 0
        ];
    }

    private function getMemberRoleChartData($ministry)
    {
        $roles = $ministry->members()
            ->selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        $labels = ['Member', 'Officer', 'Assistant Ministry Head'];
        $data = [];
        $colors = ['#0d5c2f', '#3b82f6', '#10b981'];

        $roleMapping = [
            'member' => 'Member',
            'officer' => 'Officer',
            'assistant_ministry_head' => 'Assistant Ministry Head'
        ];

        foreach ($labels as $label) {
            $roleKey = array_search($label, $roleMapping);
            $data[] = $roles[$roleKey] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getBudgetStatusChartData($ministry)
    {
        $statuses = $ministry->budgetRequests()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = ['Approved', 'Pending', 'Rejected'];
        $data = [];
        $colors = ['#10b981', '#f59e0b', '#ef4444'];

        foreach ($labels as $label) {
            $data[] = $statuses[strtolower($label)] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'isEmpty' => array_sum($data) === 0
        ];
    }

    private function getMemberStatusChartData($ministry)
    {
        $activeMembers = $ministry->members()->where('is_active', true)->count();
        $inactiveMembers = $ministry->members()->where('is_active', false)->count();

        return [
            'labels' => ['Active', 'Inactive'],
            'data' => [$activeMembers, $inactiveMembers],
            'colors' => ['#10b981', '#ef4444'],
            'isEmpty' => ($activeMembers + $inactiveMembers) === 0
        ];
    }

    private function getRecentActivities($ministry)
    {
        return $ministry->activities()
            ->orderBy('start_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($activity) {
                $status = 'upcoming';
                $statusColor = 'green';
                $statusText = 'Upcoming';

                if ($activity->start_at->isPast()) {
                    $status = 'completed';
                    $statusColor = 'blue';
                    $statusText = 'Completed';
                } elseif ($activity->start_at->isToday()) {
                    $status = 'ongoing';
                    $statusColor = 'yellow';
                    $statusText = 'Ongoing';
                }

                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'date' => $activity->start_at->format('M d, Y'),
                    'status' => $status,
                    'status_color' => $statusColor,
                    'status_text' => $statusText,
                    'icon' => $this->getActivityIcon($activity->title)
                ];
            });
    }

    private function getActivityIcon($title)
    {
        $title = strtolower($title);
        
        if (str_contains($title, 'prayer') || str_contains($title, 'mass')) {
            return 'fas fa-calendar';
        } elseif (str_contains($title, 'outreach') || str_contains($title, 'community')) {
            return 'fas fa-users';
        } elseif (str_contains($title, 'fund') || str_contains($title, 'fundraising')) {
            return 'fas fa-coins';
        } else {
            return 'fas fa-calendar-alt';
        }
    }
}


