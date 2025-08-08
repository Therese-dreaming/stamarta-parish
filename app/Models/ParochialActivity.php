<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ParochialActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'block_type',
        'location',
        'organizer',
        'contact_person',
        'contact_phone',
        'contact_email',
        'status',
        'is_recurring',
        'recurring_pattern',
        'recurring_end_date',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_recurring' => 'boolean',
        'recurring_pattern' => 'array',
        'recurring_end_date' => 'date',
    ];

    protected $appends = [
        'formatted_date',
        'formatted_time',
        'formatted_datetime',
        'is_blocking_bookings',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // Block type constants
    const BLOCK_TYPE_TIME_SLOT = 'time_slot';
    const BLOCK_TYPE_FULL_DAY = 'full_day';

    /**
     * Get formatted date attribute
     */
    public function getFormattedDateAttribute()
    {
        if ($this->is_recurring) {
            return $this->event_date->format('l'); // Returns day name (Monday, Tuesday, etc.)
        }
        return $this->event_date->format('F j, Y');
    }

    /**
     * Get formatted time attribute
     */
    public function getFormattedTimeAttribute()
    {
        return $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
    }

    /**
     * Get formatted datetime attribute
     */
    public function getFormattedDatetimeAttribute()
    {
        if ($this->is_recurring) {
            return $this->event_date->format('l') . ' at ' . $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
        }
        return $this->event_date->format('F j, Y') . ' at ' . $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
    }

    /**
     * Check if this activity is currently blocking bookings
     */
    public function getIsBlockingBookingsAttribute()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if a specific date and time conflicts with this activity
     */
    public function conflictsWithBooking($bookingDate, $bookingTime)
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $bookingDateObj = Carbon::parse($bookingDate);
        $activityDateObj = Carbon::parse($this->event_date);

        // For recurring activities, check if the booking date matches the day of the week
        if ($this->is_recurring) {
            if ($bookingDateObj->format('l') !== $activityDateObj->format('l')) {
                return false; // Different day of the week
            }
        } else {
            // For non-recurring activities, check exact date match
            if (!$this->event_date->equalTo($bookingDate)) {
                return false;
            }
        }

        // If blocking full day, any booking on that date conflicts
        if ($this->block_type === self::BLOCK_TYPE_FULL_DAY) {
            return true;
        }

        // If blocking time slot, check time overlap
        if ($this->block_type === self::BLOCK_TYPE_TIME_SLOT) {
            $bookingTimeObj = Carbon::parse($bookingTime);
            $activityStart = Carbon::parse($bookingDate . ' ' . $this->start_time->format('H:i:s'));
            $activityEnd = Carbon::parse($bookingDate . ' ' . $this->end_time->format('H:i:s'));

            // Check if booking time overlaps with activity time
            return $bookingTimeObj->between($activityStart, $activityEnd) || 
                   $activityStart->between($bookingTimeObj, $bookingTimeObj->addMinutes(60)) ||
                   $activityEnd->between($bookingTimeObj, $bookingTimeObj->addMinutes(60));
        }

        return false;
    }

    /**
     * Get all dates this activity affects (including recurring dates)
     */
    public function getAffectedDates()
    {
        if (!$this->is_recurring) {
            return [$this->event_date];
        }

        $dates = [];
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::parse($this->recurring_end_date ?? Carbon::now()->addYear());

        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            // Check if this date matches the day of the week for the activity
            if ($currentDate->format('l') === $this->event_date->format('l')) {
                $dates[] = $currentDate->copy();
            }
            $currentDate->addDay();
        }

        return $dates;
    }

    /**
     * Scope for active activities
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for activities on a specific date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->where('event_date', $date);
    }

    /**
     * Scope for activities that block bookings
     */
    public function scopeBlockingBookings($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for upcoming activities
     */
    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('event_date', '>=', now()->startOfDay())
                    ->where('event_date', '<=', now()->addDays($days));
    }

    /**
     * Scope for past activities
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->startOfDay());
    }

    /**
     * Get status badge attribute
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>';
            case self::STATUS_CANCELLED:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>';
            case self::STATUS_COMPLETED:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Completed</span>';
            default:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>';
        }
    }

    /**
     * Get block type label
     */
    public function getBlockTypeLabelAttribute()
    {
        switch ($this->block_type) {
            case self::BLOCK_TYPE_TIME_SLOT:
                return 'Time Slot Only';
            case self::BLOCK_TYPE_FULL_DAY:
                return 'Full Day';
            default:
                return 'Unknown';
        }
    }

    /**
     * Get calendar color (always yellow for parochial activities)
     */
    public function getCalendarColorAttribute()
    {
        return '#fbbf24'; // Yellow color
    }

    /**
     * Get the user who created this activity
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this activity
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
