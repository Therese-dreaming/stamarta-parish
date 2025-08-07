<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'action_type',
        'notes',
        'performed_by',
        'priest_id',
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