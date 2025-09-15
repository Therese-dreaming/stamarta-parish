<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',        // Links action to specific booking, used for tracking booking workflow history
        'action_type',       // Type of action performed (acknowledged, approved, rejected, completed), determines UI display and workflow state
        'notes',             // Optional notes explaining the action, displayed in timeline views for context and documentation
        'performed_by',      // User ID who performed the action, used for audit trail and accountability tracking
        'priest_id',         // Optional priest ID when action involves priest assignment, used for priest-specific action tracking
    ];

    // Action type constants
    const ACTION_ACKNOWLEDGED = 'acknowledged';
    const ACTION_APPROVED = 'approved';
    const ACTION_REJECTED = 'rejected';
    const ACTION_COMPLETED = 'completed';

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function priest()
    {
        return $this->belongsTo(Priest::class);
    }

    public function getActionTypeLabelAttribute()
    {
        return ucfirst($this->action_type);
    }

    public function getActionIconAttribute()
    {
        return match($this->action_type) {
            self::ACTION_ACKNOWLEDGED => 'fas fa-check',
            self::ACTION_APPROVED => 'fas fa-check-circle',
            self::ACTION_REJECTED => 'fas fa-times',
            self::ACTION_COMPLETED => 'fas fa-flag-checkered',
            default => 'fas fa-info-circle',
        };
    }

    public function getActionColorAttribute()
    {
        return match($this->action_type) {
            self::ACTION_ACKNOWLEDGED => 'blue',
            self::ACTION_APPROVED => 'green',
            self::ACTION_REJECTED => 'red',
            self::ACTION_COMPLETED => 'green',
            default => 'gray',
        };
    }
} 