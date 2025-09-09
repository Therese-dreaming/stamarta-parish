<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'action',
        'message',
        'data',
        'booking_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Notification types
    const TYPE_USER = 'user';
    const TYPE_ADMIN = 'admin';
    const TYPE_STAFF = 'staff';
    const TYPE_PRIEST = 'priest';

    // Action types for user notifications
    const ACTION_BOOKING_CREATED = 'booking_created';
    const ACTION_BOOKING_ACKNOWLEDGED = 'booking_acknowledged';
    const ACTION_BOOKING_REJECTED = 'booking_rejected';
    const ACTION_PAYMENT_SUBMITTED = 'payment_submitted';
    const ACTION_PAYMENT_VERIFIED = 'payment_verified';
    const ACTION_BOOKING_APPROVED = 'booking_approved';

    // Action types for admin notifications
    const ACTION_USER_BOOKING_CREATED = 'user_booking_created';
    const ACTION_USER_PAYMENT_SUBMITTED = 'user_payment_submitted';
    const ACTION_USER_BOOKING_CANCELLED = 'user_booking_cancelled';
    const ACTION_USER_CONTACT_MESSAGE = 'user_contact_message';

    // Action types for staff notifications (admin receives these)
    const ACTION_STAFF_BOOKING_ACKNOWLEDGED = 'staff_booking_acknowledged';
    const ACTION_STAFF_BOOKING_APPROVED = 'staff_booking_approved';
    const ACTION_STAFF_BOOKING_REJECTED = 'staff_booking_rejected';
    const ACTION_STAFF_PAGE_CREATED = 'staff_page_created';
    const ACTION_STAFF_ACTIVITY_CREATED = 'staff_activity_created';

    // Action types for priest notifications (admin receives these)
    const ACTION_PRIEST_PROFILE_EDITED = 'priest_profile_edited';
    const ACTION_PRIEST_LEAVE_FILED = 'priest_leave_filed';
    
    // Action types for priest notifications (priest receives these)
    const ACTION_PRIEST_LEAVE_APPROVED = 'priest_leave_approved';
    const ACTION_PRIEST_LEAVE_REJECTED = 'priest_leave_rejected';
    const ACTION_PRIEST_BOOKING_ASSIGNED = 'priest_booking_assigned';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
