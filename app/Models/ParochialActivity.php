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
        'title',                // Activity title displayed in lists and calendars, used for identification and display
        'description',          // Detailed description of the activity, used for documentation and information display
        'event_date',           // Date when the activity occurs, used for scheduling and conflict detection
        'start_time',           // Activity start time, used for time slot blocking and conflict detection
        'end_time',             // Activity end time, used for duration calculation and time slot blocking
        'block_type',           // Type of blocking (time_slot or full_day), used for booking conflict determination
        'location',             // Activity location, used for display and organization purposes
        'organizer',            // Person or organization organizing the activity, used for identification
        'contact_person',       // Contact person for the activity, used for communication and coordination
        'contact_phone',        // Phone number for activity contact, used for communication purposes
        'contact_email',        // Email address for activity contact, used for communication and notifications
        'status',               // Activity status (active, cancelled, completed), used for blocking logic and display
        'is_recurring',         // Boolean flag for recurring activities, used for weekly pattern conflict detection
        'recurring_pattern',    // JSON object storing recurrence type and interval, used for generating recurring dates
        'recurring_end_date',   // End date for recurring activities, used for limiting recurrence scope
        'notes',                // Additional notes about the activity, used for internal documentation
        'created_by',           // User ID who created the activity, used for audit trail and ownership
        'updated_by',           // User ID who last updated the activity, used for audit trail and change tracking
        'deleted_at',           // Timestamp when activity was soft deleted, used for soft delete functionality and data recovery
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
        'block_type_label',
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

        // For recurring activities, check if the booking date matches the recurrence pattern
        if ($this->is_recurring) {
            // Respect recurring_end_date if set
            if ($this->recurring_end_date && $bookingDateObj->gt(Carbon::parse($this->recurring_end_date))) {
                return false;
            }

            $pattern = $this->recurring_pattern ?? [];
            $type = $pattern['type'] ?? 'weekly';

            if ($type === 'weekly') {
                if ($bookingDateObj->format('l') !== $activityDateObj->format('l')) {
                    return false; // Different day of the week
                }
            } elseif ($type === 'monthly') {
                if ((int)$bookingDateObj->format('j') !== (int)$activityDateObj->format('j')) {
                    return false; // Different day of the month
                }
            } elseif ($type === 'yearly') {
                if ($bookingDateObj->format('m-d') !== $activityDateObj->format('m-d')) {
                    return false; // Different month-day
                }
            } else {
                // Unknown pattern, default to no block
                return false;
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
            // Ensure event_date is a Carbon instance
            return [$this->event_date instanceof Carbon ? $this->event_date : Carbon::parse($this->event_date)];
        }

        $dates = [];
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::parse($this->recurring_end_date ?? Carbon::now()->addYear());

        $pattern = $this->recurring_pattern ?? [];
        $type = $pattern["type"] ?? 'weekly';

        if ($type === 'weekly') {
            // Add every matching weekday between start and end
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                if ($currentDate->format('l') === $this->event_date->format('l')) {
                    $dates[] = $currentDate->copy();
                }
                $currentDate->addDay();
            }
        } elseif ($type === 'monthly') {
            // Add same day-of-month each month
            $day = (int) Carbon::parse($this->event_date)->format('j');
            $cursor = $startDate->copy()->day(1);
            while ($cursor->lte($endDate)) {
                $candidate = $cursor->copy()->day(min($day, $cursor->daysInMonth));
                if ($candidate->betweenIncluded($startDate, $endDate)) {
                    $dates[] = $candidate->copy();
                }
                $cursor->addMonth();
            }
        } elseif ($type === 'yearly') {
            // Add same month-day each year
            $month = (int) Carbon::parse($this->event_date)->format('n');
            $day = (int) Carbon::parse($this->event_date)->format('j');
            $cursor = $startDate->copy()->startOfYear();
            while ($cursor->lte($endDate)) {
                $candidate = $cursor->copy()->month($month);
                // Adjust for shorter months (e.g., Feb 29 -> Feb 28 if non-leap)
                $candidate->day(min($day, $candidate->daysInMonth));
                if ($candidate->betweenIncluded($startDate, $endDate)) {
                    $dates[] = $candidate->copy();
                }
                $cursor->addYear();
            }
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
        return $query->whereDate('event_date', $date);
    }

    /**
     * Scope for activities that affect a specific date (includes recurring)
     */
    public function scopeAffectingDate($query, $date)
    {
        $targetDate = Carbon::parse($date);
        $dateStr = $targetDate->format('Y-m-d');
        $dayOfWeek = $targetDate->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        $dayName = $targetDate->format('l'); // Full day name (Monday, Tuesday, etc.)
        $dayOfMonth = $targetDate->day;
        $monthDay = $targetDate->format('m-d');
        
        return $query->where(function ($q) use ($dateStr, $dayOfWeek, $dayName, $dayOfMonth, $monthDay, $targetDate) {
            // Non-recurring activities on the exact date
            $q->where(function($subQ) use ($dateStr) {
                $subQ->where('is_recurring', false)
                     ->whereDate('event_date', $dateStr);
            })
            // Recurring weekly activities that match the day of week
            ->orWhere(function($subQ) use ($dateStr, $dayName) {
                $subQ->where('is_recurring', true)
                     ->whereRaw("JSON_EXTRACT(recurring_pattern, '$.type') = 'weekly'")
                     ->whereRaw("DAYNAME(event_date) = ?", [$dayName])
                     ->where(function ($qq) use ($dateStr) {
                         $qq->whereNull('recurring_end_date')
                            ->orWhereDate('recurring_end_date', '>=', $dateStr);
                     });
            })
            // Recurring monthly activities that match the day of month
            ->orWhere(function($subQ) use ($dateStr, $dayOfMonth) {
                $subQ->where('is_recurring', true)
                     ->whereRaw("JSON_EXTRACT(recurring_pattern, '$.type') = 'monthly'")
                     ->whereRaw("DAY(event_date) = ?", [$dayOfMonth])
                     ->where(function ($qq) use ($dateStr) {
                         $qq->whereNull('recurring_end_date')
                            ->orWhereDate('recurring_end_date', '>=', $dateStr);
                     });
            })
            // Recurring yearly activities that match the month and day
            ->orWhere(function($subQ) use ($dateStr, $monthDay) {
                $subQ->where('is_recurring', true)
                     ->whereRaw("JSON_EXTRACT(recurring_pattern, '$.type') = 'yearly'")
                     ->whereRaw("DATE_FORMAT(event_date, '%m-%d') = ?", [$monthDay])
                     ->where(function ($qq) use ($dateStr) {
                         $qq->whereNull('recurring_end_date')
                            ->orWhereDate('recurring_end_date', '>=', $dateStr);
                     });
            });
        });
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
