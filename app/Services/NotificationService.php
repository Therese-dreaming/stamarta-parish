<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;

class NotificationService
{
    /**
     * Create a notification for admin/staff actions
     */
    public static function createAdminStaffNotification($action, $message, $data = [], $userId = null, $bookingId = null)
    {
        return Notification::create([
            'type' => Notification::TYPE_ADMIN_STAFF,
            'action' => $action,
            'message' => $message,
            'data' => $data,
            'user_id' => $userId,
            'created_by' => auth()->id() ?? null,
            'booking_id' => $bookingId,
        ]);
    }

    /**
     * Create a notification for user actions
     */
    public static function createUserNotification($action, $message, $data = [], $userId = null, $bookingId = null)
    {
        return Notification::create([
            'type' => Notification::TYPE_USER,
            'action' => $action,
            'message' => $message,
            'data' => $data,
            'user_id' => $userId,
            'created_by' => auth()->id() ?? null,
            'booking_id' => $bookingId,
        ]);
    }

    /**
     * Notify when a booking is created
     */
    public static function bookingCreated(Booking $booking)
    {
        // Notify admin/staff about new booking
        self::createAdminStaffNotification(
            Notification::ACTION_BOOKING_CREATED,
            "New booking #{$booking->id} created by {$booking->user->name} for {$booking->service->name}",
            [
                'booking_id' => $booking->id,
                'user_name' => $booking->user->name,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date->format('M d, Y'),
                'service_time' => $booking->formatted_time
            ],
            null, // Notify all admin/staff
            $booking->id
        );

        // Notify user about their booking
        self::createUserNotification(
            Notification::ACTION_BOOKING_CREATED,
            "Your booking #{$booking->id} for {$booking->service->name} has been submitted successfully",
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'service_date' => $booking->service_date->format('M d, Y'),
                'service_time' => $booking->formatted_time
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Notify when a booking is acknowledged
     */
    public static function bookingAcknowledged(Booking $booking)
    {
        $userName = auth()->user() ? auth()->user()->name : 'Parish Staff';
        
        self::createUserNotification(
            Notification::ACTION_BOOKING_ACKNOWLEDGED,
            "Your booking #{$booking->id} has been acknowledged by parish staff",
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'acknowledged_by' => $userName
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Notify when a booking is approved
     */
    public static function bookingApproved(Booking $booking)
    {
        $userName = auth()->user() ? auth()->user()->name : 'Parish Staff';
        
        self::createUserNotification(
            Notification::ACTION_BOOKING_APPROVED,
            "Your booking #{$booking->id} has been approved!",
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'approved_by' => $userName
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Notify when a booking is rejected
     */
    public static function bookingRejected(Booking $booking, $reason = null)
    {
        $userName = auth()->user() ? auth()->user()->name : 'Parish Staff';
        
        self::createUserNotification(
            Notification::ACTION_BOOKING_REJECTED,
            "Your booking #{$booking->id} has been rejected" . ($reason ? ": {$reason}" : ""),
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'rejected_by' => $userName,
                'reason' => $reason
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Notify when a booking is completed
     */
    public static function bookingCompleted(Booking $booking)
    {
        $userName = auth()->user() ? auth()->user()->name : 'Parish Staff';
        
        self::createUserNotification(
            Notification::ACTION_BOOKING_COMPLETED,
            "Your booking #{$booking->id} has been completed successfully",
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'completed_by' => $userName
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Notify when payment is verified
     */
    public static function paymentVerified(Booking $booking)
    {
        $userName = auth()->user() ? auth()->user()->name : 'Parish Staff';
        
        self::createUserNotification(
            Notification::ACTION_PAYMENT_VERIFIED,
            "Payment for booking #{$booking->id} has been verified",
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'payment_amount' => $booking->payment->total_fee ?? 'N/A',
                'verified_by' => $userName
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Notify when payment is rejected
     */
    public static function paymentRejected(Booking $booking, $reason = null)
    {
        $userName = auth()->user() ? auth()->user()->name : 'Parish Staff';
        
        self::createUserNotification(
            Notification::ACTION_PAYMENT_REJECTED,
            "Payment for booking #{$booking->id} has been rejected" . ($reason ? ": {$reason}" : ""),
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'rejected_by' => $userName,
                'reason' => $reason
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Notify when a new user registers
     */
    public static function userRegistered(User $user)
    {
        self::createAdminStaffNotification(
            Notification::ACTION_USER_REGISTERED,
            "New user registered: {$user->name} ({$user->email})",
            [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email
            ]
        );
    }

    /**
     * Notify when a priest is assigned to a booking
     */
    public static function priestAssigned(Booking $booking, $priestName)
    {
        self::createUserNotification(
            Notification::ACTION_PRIEST_ASSIGNED,
            "Priest {$priestName} has been assigned to your booking #{$booking->id}",
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'priest_name' => $priestName
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Notify when a priest is changed for a booking
     */
    public static function priestChanged(Booking $booking, $oldPriestName, $newPriestName)
    {
        self::createUserNotification(
            Notification::ACTION_PRIEST_CHANGED,
            "Priest for your booking #{$booking->id} has been changed from {$oldPriestName} to {$newPriestName}",
            [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'old_priest_name' => $oldPriestName,
                'new_priest_name' => $newPriestName
            ],
            $booking->user_id,
            $booking->id
        );
    }

    /**
     * Get unread notification count for a user
     */
    public static function getUnreadCount($userId = null)
    {
        $query = Notification::unread();
        
        if ($userId) {
            $query->forUser($userId);
        }
        
        return $query->count();
    }

    /**
     * Mark notifications as read
     */
    public static function markAsRead($notificationIds, $userId = null)
    {
        $query = Notification::whereIn('id', $notificationIds);
        
        if ($userId) {
            $query->forUser($userId);
        }
        
        return $query->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }
} 