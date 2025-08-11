<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'action',
        'message',
        'data',
        'user_id',
        'created_by',
        'booking_id',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Notification types
    const TYPE_ADMIN_STAFF = 'admin_staff';
    const TYPE_USER = 'user';

    // Action types
    const ACTION_BOOKING_CREATED = 'booking_created';
    const ACTION_BOOKING_ACKNOWLEDGED = 'booking_acknowledged';
    const ACTION_BOOKING_APPROVED = 'booking_approved';
    const ACTION_BOOKING_REJECTED = 'booking_rejected';
    const ACTION_BOOKING_COMPLETED = 'booking_completed';
    const ACTION_PAYMENT_VERIFIED = 'payment_verified';
    const ACTION_PAYMENT_REJECTED = 'payment_rejected';
    const ACTION_USER_REGISTERED = 'user_registered';
    const ACTION_PRIEST_ASSIGNED = 'priest_assigned';
    const ACTION_PRIEST_CHANGED = 'priest_changed';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Scopes
    public function scopeAdminStaff($query)
    {
        return $query->where('type', self::TYPE_ADMIN_STAFF);
    }

    public function scopeUser($query)
    {
        return $query->where('type', self::TYPE_USER);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function getIconAttribute()
    {
        return match($this->action) {
            self::ACTION_BOOKING_CREATED => 'fas fa-calendar-plus',
            self::ACTION_BOOKING_ACKNOWLEDGED => 'fas fa-eye',
            self::ACTION_BOOKING_APPROVED => 'fas fa-check-circle',
            self::ACTION_BOOKING_REJECTED => 'fas fa-times-circle',
            self::ACTION_BOOKING_COMPLETED => 'fas fa-flag-checkered',
            self::ACTION_PAYMENT_VERIFIED => 'fas fa-credit-card',
            self::ACTION_PAYMENT_REJECTED => 'fas fa-exclamation-triangle',
            self::ACTION_USER_REGISTERED => 'fas fa-user-plus',
            self::ACTION_PRIEST_ASSIGNED => 'fas fa-cross',
            self::ACTION_PRIEST_CHANGED => 'fas fa-exchange-alt',
            default => 'fas fa-bell'
        };
    }

    public function getColorAttribute()
    {
        return match($this->action) {
            self::ACTION_BOOKING_CREATED => 'text-blue-600',
            self::ACTION_BOOKING_ACKNOWLEDGED => 'text-blue-600',
            self::ACTION_BOOKING_APPROVED => 'text-green-600',
            self::ACTION_BOOKING_REJECTED => 'text-red-600',
            self::ACTION_BOOKING_COMPLETED => 'text-purple-600',
            self::ACTION_PAYMENT_VERIFIED => 'text-green-600',
            self::ACTION_PAYMENT_REJECTED => 'text-red-600',
            self::ACTION_USER_REGISTERED => 'text-blue-600',
            self::ACTION_PRIEST_ASSIGNED => 'text-indigo-600',
            self::ACTION_PRIEST_CHANGED => 'text-orange-600',
            default => 'text-gray-600'
        };
    }

    public function getBadgeColorAttribute()
    {
        return match($this->action) {
            self::ACTION_BOOKING_CREATED => 'bg-blue-100 text-blue-800',
            self::ACTION_BOOKING_ACKNOWLEDGED => 'bg-blue-100 text-blue-800',
            self::ACTION_BOOKING_APPROVED => 'bg-green-100 text-green-800',
            self::ACTION_BOOKING_REJECTED => 'bg-red-100 text-red-800',
            self::ACTION_BOOKING_COMPLETED => 'bg-purple-100 text-purple-800',
            self::ACTION_PAYMENT_VERIFIED => 'bg-green-100 text-green-800',
            self::ACTION_PAYMENT_REJECTED => 'bg-red-100 text-red-800',
            self::ACTION_USER_REGISTERED => 'bg-blue-100 text-blue-800',
            self::ACTION_PRIEST_ASSIGNED => 'bg-indigo-100 text-indigo-800',
            self::ACTION_PRIEST_CHANGED => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
