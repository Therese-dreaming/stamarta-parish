<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Booking;

class NotificationService
{
    /**
     * Create a user notification
     */
    public static function createUserNotification($action, $message, $userId, $bookingId = null, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => Notification::TYPE_USER,
            'action' => $action,
            'message' => $message,
            'data' => $data,
            'booking_id' => $bookingId,
        ]);
    }

    /**
     * Notify when a booking is created
     */
    public static function bookingCreated(Booking $booking)
    {
        return self::createUserNotification(
            Notification::ACTION_BOOKING_CREATED,
            "Your booking #{$booking->id} for {$booking->service->name} has been submitted successfully. We will review your request and contact you soon.",
            $booking->user_id,
            $booking->id,
            [
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'status' => $booking->status,
            ]
        );
    }

    /**
     * Notify when a booking is acknowledged
     */
    public static function bookingAcknowledged(Booking $booking)
    {
        return self::createUserNotification(
            Notification::ACTION_BOOKING_ACKNOWLEDGED,
            "Your booking #{$booking->id} has been acknowledged by our staff. You can now submit your payment proof.",
            $booking->user_id,
            $booking->id,
            [
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'total_fee' => $booking->total_fee,
                'status' => $booking->status,
            ]
        );
    }

    /**
     * Notify when a booking is rejected
     */
    public static function bookingRejected(Booking $booking, $reason = null)
    {
        $message = "Your booking #{$booking->id} for {$booking->service->name} has been rejected.";
        if ($reason) {
            $message .= " Reason: {$reason}";
        }

        return self::createUserNotification(
            Notification::ACTION_BOOKING_REJECTED,
            $message,
            $booking->user_id,
            $booking->id,
            [
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'reason' => $reason,
                'status' => $booking->status,
            ]
        );
    }

    /**
     * Notify when payment proof is submitted
     */
    public static function paymentSubmitted(Booking $booking)
    {
        return self::createUserNotification(
            Notification::ACTION_PAYMENT_SUBMITTED,
            "Payment proof for booking #{$booking->id} has been submitted successfully. Our staff will verify your payment and contact you soon.",
            $booking->user_id,
            $booking->id,
            [
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'amount' => $booking->total_fee,
                'status' => $booking->status,
            ]
        );
    }

    /**
     * Notify when payment is verified
     */
    public static function paymentVerified(Booking $booking)
    {
        return self::createUserNotification(
            Notification::ACTION_PAYMENT_VERIFIED,
            "Your payment for booking #{$booking->id} has been verified successfully.",
            $booking->user_id,
            $booking->id,
            [
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'amount' => $booking->total_fee,
                'status' => $booking->status,
            ]
        );
    }

    /**
     * Notify when booking is approved (after payment verification)
     */
    public static function bookingApproved(Booking $booking)
    {
        return self::createUserNotification(
            Notification::ACTION_BOOKING_APPROVED,
            "Congratulations! Your booking #{$booking->id} for {$booking->service->name} has been approved. Your service is scheduled for " . $booking->service_date->format('F j, Y') . ".",
            $booking->user_id,
            $booking->id,
            [
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'priest_name' => $booking->priest ? $booking->priest->name : 'TBA',
                'status' => $booking->status,
            ]
        );
    }

    /**
     * Mark notifications as read
     */
    public static function markAsRead($notificationIds, $userId = null)
    {
        $query = Notification::whereIn('id', $notificationIds);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Create an admin notification
     */
    public static function createAdminNotification($action, $message, $data = [], $bookingId = null)
    {
        // Get all admin users
        $adminUsers = \App\Models\User::where('role', 'admin')->pluck('id');
        
        $notifications = [];
        foreach ($adminUsers as $adminId) {
            $notifications[] = Notification::create([
                'user_id' => $adminId,
                'type' => Notification::TYPE_ADMIN,
                'action' => $action,
                'message' => $message,
                'data' => $data,
                'booking_id' => $bookingId,
            ]);
        }
        
        return $notifications;
    }

    /**
     * Create a staff notification
     */
    public static function createStaffNotification($action, $message, $data = [], $bookingId = null)
    {
        // Get all staff users
        $staffUsers = \App\Models\User::where('role', 'staff')->pluck('id');
        
        $notifications = [];
        foreach ($staffUsers as $staffId) {
            $notifications[] = Notification::create([
                'user_id' => $staffId,
                'type' => Notification::TYPE_STAFF,
                'action' => $action,
                'message' => $message,
                'data' => $data,
                'booking_id' => $bookingId,
            ]);
        }
        
        return $notifications;
    }

    /**
     * Create a priest notification
     */
    public static function createPriestNotification($action, $message, $data = [], $bookingId = null)
    {
        // Get all priest users
        $priestUsers = \App\Models\User::where('role', 'priest')->pluck('id');
        
        $notifications = [];
        foreach ($priestUsers as $priestId) {
            $notifications[] = Notification::create([
                'user_id' => $priestId,
                'type' => Notification::TYPE_PRIEST,
                'action' => $action,
                'message' => $message,
                'data' => $data,
                'booking_id' => $bookingId,
            ]);
        }
        
        return $notifications;
    }

    /**
     * Notify admins and staff when a user creates a booking
     */
    public static function userBookingCreated(Booking $booking)
    {
        // Notify admins
        self::createAdminNotification(
            Notification::ACTION_USER_BOOKING_CREATED,
            "New booking #{$booking->id} created by {$booking->user->name} for {$booking->service->name} on " . $booking->service_date->format('F j, Y'),
            [
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'total_fee' => $booking->total_fee,
                'status' => $booking->status,
            ],
            $booking->id
        );

        // Notify staff
        self::createStaffNotification(
            Notification::ACTION_USER_BOOKING_CREATED,
            "New booking #{$booking->id} created by {$booking->user->name} for {$booking->service->name} on " . $booking->service_date->format('F j, Y'),
            [
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'total_fee' => $booking->total_fee,
                'status' => $booking->status,
            ],
            $booking->id
        );
    }

    /**
     * Notify admins and staff when a user submits payment
     */
    public static function userPaymentSubmitted(Booking $booking)
    {
        // Notify admins
        self::createAdminNotification(
            Notification::ACTION_USER_PAYMENT_SUBMITTED,
            "Payment proof submitted by {$booking->user->name} for booking #{$booking->id} - {$booking->service->name}",
            [
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'amount' => $booking->total_fee,
                'status' => $booking->status,
            ],
            $booking->id
        );

        // Notify staff
        self::createStaffNotification(
            Notification::ACTION_USER_PAYMENT_SUBMITTED,
            "Payment proof submitted by {$booking->user->name} for booking #{$booking->id} - {$booking->service->name}",
            [
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'amount' => $booking->total_fee,
                'status' => $booking->status,
            ],
            $booking->id
        );
    }

    /**
     * Notify admins and staff when a user cancels a booking
     */
    public static function userBookingCancelled(Booking $booking, $reason = null)
    {
        $message = "Booking #{$booking->id} cancelled by {$booking->user->name} for {$booking->service->name}";
        if ($reason) {
            $message .= ". Reason: {$reason}";
        }

        // Notify admins
        self::createAdminNotification(
            Notification::ACTION_USER_BOOKING_CANCELLED,
            $message,
            [
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'reason' => $reason,
                'status' => $booking->status,
            ],
            $booking->id
        );

        // Notify staff
        self::createStaffNotification(
            Notification::ACTION_USER_BOOKING_CANCELLED,
            $message,
            [
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'reason' => $reason,
                'status' => $booking->status,
            ],
            $booking->id
        );
    }

    /**
     * Notify admins and staff when a user sends a contact message
     */
    public static function userContactMessage($user, $subject, $message)
    {
        // Notify admins
        self::createAdminNotification(
            Notification::ACTION_USER_CONTACT_MESSAGE,
            "New contact message from {$user->name}: {$subject}",
            [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'subject' => $subject,
                'message' => $message,
            ]
        );

        // Notify staff
        self::createStaffNotification(
            Notification::ACTION_USER_CONTACT_MESSAGE,
            "New contact message from {$user->name}: {$subject}",
            [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'subject' => $subject,
                'message' => $message,
            ]
        );
    }

    /**
     * Notify admins when staff acknowledges a booking
     */
    public static function staffBookingAcknowledged(Booking $booking, $staffName)
    {
        return self::createAdminNotification(
            Notification::ACTION_STAFF_BOOKING_ACKNOWLEDGED,
            "Staff {$staffName} acknowledged booking #{$booking->id} for {$booking->service->name}",
            [
                'staff_name' => $staffName,
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'total_fee' => $booking->total_fee,
                'status' => $booking->status,
            ],
            $booking->id
        );
    }

    /**
     * Notify admins when staff approves a booking
     */
    public static function staffBookingApproved(Booking $booking, $staffName)
    {
        return self::createAdminNotification(
            Notification::ACTION_STAFF_BOOKING_APPROVED,
            "Staff {$staffName} approved booking #{$booking->id} for {$booking->service->name}",
            [
                'staff_name' => $staffName,
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'priest_name' => $booking->priest ? $booking->priest->name : 'TBA',
                'status' => $booking->status,
            ],
            $booking->id
        );
    }

    /**
     * Notify admins when staff rejects a booking
     */
    public static function staffBookingRejected(Booking $booking, $staffName, $reason = null)
    {
        $message = "Staff {$staffName} rejected booking #{$booking->id} for {$booking->service->name}";
        if ($reason) {
            $message .= ". Reason: {$reason}";
        }

        return self::createAdminNotification(
            Notification::ACTION_STAFF_BOOKING_REJECTED,
            $message,
            [
                'staff_name' => $staffName,
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'reason' => $reason,
                'status' => $booking->status,
            ],
            $booking->id
        );
    }

    /**
     * Notify admins when staff creates a page
     */
    public static function staffPageCreated($page, $staffName)
    {
        return self::createAdminNotification(
            Notification::ACTION_STAFF_PAGE_CREATED,
            "Staff {$staffName} created a new page: {$page->title}",
            [
                'staff_name' => $staffName,
                'page_title' => $page->title,
                'page_slug' => $page->slug,
                'page_status' => $page->status,
            ]
        );
    }

    /**
     * Notify admins when staff creates a parochial activity
     */
    public static function staffActivityCreated($activity, $staffName)
    {
        return self::createAdminNotification(
            Notification::ACTION_STAFF_ACTIVITY_CREATED,
            "Staff {$staffName} created a new parochial activity: {$activity->title}",
            [
                'staff_name' => $staffName,
                'activity_title' => $activity->title,
                'activity_date' => $activity->date,
                'activity_status' => $activity->status,
            ]
        );
    }

    /**
     * Notify admins when a priest edits their profile
     */
    public static function priestProfileEdited($priest, $changes = [])
    {
        $message = "Priest {$priest->name} updated their profile";
        if (!empty($changes)) {
            $message .= ". Changes: " . implode(', ', $changes);
        }

        return self::createAdminNotification(
            Notification::ACTION_PRIEST_PROFILE_EDITED,
            $message,
            [
                'priest_name' => $priest->name,
                'priest_id' => $priest->id,
                'changes' => $changes,
            ]
        );
    }

    /**
     * Notify admins when a priest files a leave
     */
    public static function priestLeaveFiled($leave)
    {
        return self::createAdminNotification(
            Notification::ACTION_PRIEST_LEAVE_FILED,
            "Priest {$leave->priest->name} filed a leave request for {$leave->leave_type} from " . $leave->start_date->format('M j') . " to " . $leave->end_date->format('M j, Y'),
            [
                'priest_name' => $leave->priest->name,
                'priest_id' => $leave->priest->id,
                'leave_type' => $leave->leave_type,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'reason' => $leave->reason,
                'status' => $leave->status,
            ],
            null // No booking ID for leave requests
        );
    }

    /**
     * Notify priest when their leave is approved
     */
    public static function priestLeaveApproved($leave)
    {
        return self::createPriestNotification(
            Notification::ACTION_PRIEST_LEAVE_APPROVED,
            "Your leave request for {$leave->leave_type} from " . $leave->start_date->format('M j') . " to " . $leave->end_date->format('M j, Y') . " has been approved",
            [
                'leave_type' => $leave->leave_type,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'reason' => $leave->reason,
                'status' => $leave->status,
            ]
        );
    }

    /**
     * Notify priest when their leave is rejected
     */
    public static function priestLeaveRejected($leave, $reason = null)
    {
        $message = "Your leave request for {$leave->leave_type} from " . $leave->start_date->format('M j') . " to " . $leave->end_date->format('M j, Y') . " has been rejected";
        if ($reason) {
            $message .= ". Reason: {$reason}";
        }

        return self::createPriestNotification(
            Notification::ACTION_PRIEST_LEAVE_REJECTED,
            $message,
            [
                'leave_type' => $leave->leave_type,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'reason' => $reason,
                'status' => $leave->status,
            ]
        );
    }

    /**
     * Notify priest when they are assigned to a booking
     */
    public static function priestBookingAssigned($booking, $priest)
    {
        return self::createPriestNotification(
            Notification::ACTION_PRIEST_BOOKING_ASSIGNED,
            "You have been assigned to a booking for {$booking->service->name} on " . $booking->service_date->format('F j, Y') . " at {$booking->service_time}",
            [
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date,
                'service_time' => $booking->service_time,
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'total_fee' => $booking->total_fee,
                'status' => $booking->status,
            ],
            $booking->id
        );
    }
}
