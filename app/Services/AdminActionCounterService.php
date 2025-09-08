<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\ManualCashInflow;
use App\Models\MinistryBudgetRequest;
use App\Models\ParochialActivity;
use App\Models\User;
use App\Models\PriestLeave;

class AdminActionCounterService
{
    /**
     * Get counts of all items requiring admin action
     */
    public function getActionCounts(): array
    {
        return [
            'pending_bookings' => $this->getPendingBookingsCount(),
            'payment_verification' => $this->getPaymentVerificationCount(),
            'pending_cash_inflows' => $this->getPendingCashInflowsCount(),
            'pending_budget_requests' => $this->getPendingBudgetRequestsCount(),
            'pending_activities' => $this->getPendingActivitiesCount(),
            'pending_users' => $this->getPendingUsersCount(),
            'pending_priest_leaves' => $this->getPendingPriestLeavesCount(),
            'total' => 0, // Will be calculated below
        ];
    }

    /**
     * Get count of bookings that need acknowledgment
     */
    private function getPendingBookingsCount(): int
    {
        return Booking::where('status', Booking::STATUS_PENDING)->count();
    }

    /**
     * Get count of payments that need verification
     */
    private function getPaymentVerificationCount(): int
    {
        return BookingPayment::where('payment_status', BookingPayment::PAYMENT_PAID)->count();
    }

    /**
     * Get count of manual cash inflows that need approval
     */
    private function getPendingCashInflowsCount(): int
    {
        return ManualCashInflow::where('status', ManualCashInflow::STATUS_PENDING)->count();
    }

    /**
     * Get count of budget requests that need approval
     */
    private function getPendingBudgetRequestsCount(): int
    {
        return MinistryBudgetRequest::where('status', 'pending')->count();
    }

    /**
     * Get count of parochial activities that need approval
     * Note: ParochialActivity doesn't have a pending status, so we'll count active ones that might need attention
     */
    private function getPendingActivitiesCount(): int
    {
        // Count activities that are active and might need admin attention
        // This could be activities that are upcoming and need review
        return ParochialActivity::where('status', ParochialActivity::STATUS_ACTIVE)
            ->where('event_date', '>=', now()->startOfDay())
            ->where('event_date', '<=', now()->addDays(7)) // Next 7 days
            ->count();
    }

    /**
     * Get count of users that might need admin attention
     * This could be new registrations or users with issues
     */
    private function getPendingUsersCount(): int
    {
        // Count users registered in the last 7 days that might need review
        return User::where('created_at', '>=', now()->subDays(7))->count();
    }

    private function getPendingPriestLeavesCount(): int
    {
        return PriestLeave::where('status', 'pending')->count();
    }

    /**
     * Get total count of all pending actions
     */
    public function getTotalActionCount(): int
    {
        $counts = $this->getActionCounts();
        unset($counts['total']); // Remove the total key
        return array_sum($counts);
    }

    /**
     * Get formatted counts for display
     */
    public function getFormattedCounts(): array
    {
        $counts = $this->getActionCounts();
        $total = array_sum(array_slice($counts, 0, -1)); // Sum all except 'total'
        $counts['total'] = $total;

        return [
            'counts' => $counts,
            'has_actions' => $total > 0,
            'formatted_total' => $total > 99 ? '99+' : (string)$total,
        ];
    }
}
