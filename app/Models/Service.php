<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'max_slots',
        'is_active',
        'requirements',
        'fees',
        'schedules',
        'notes',
        'booking_restrictions',
        'custom_fields',
        'service_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requirements' => 'array',
        'fees' => 'array',
        'schedules' => 'array',
        'booking_restrictions' => 'array',
        'custom_fields' => 'array',
    ];

    protected $appends = [
        'formatted_duration',
        'formatted_fees',
    ];

    public function getFormattedDurationAttribute()
    {
        if ($this->duration_minutes < 60) {
            return $this->duration_minutes . ' minutes';
        }
        
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($minutes == 0) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '');
        }
        
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' minutes';
    }

    public function getFormattedFeesAttribute()
    {
        if (empty($this->fees)) {
            return 'Contact office for pricing';
        }
        
        $formatted = [];
        foreach ($this->fees as $type => $feeData) {
            if (is_array($feeData) && isset($feeData['amount'])) {
                $description = $feeData['description'] ?? $type;
                $formatted[] = $description . ': ₱' . number_format($feeData['amount'], 2);
            } else {
                $formatted[] = ucfirst($type) . ': ₱' . number_format($feeData, 2);
            }
        }
        
        return implode(', ', $formatted);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function getScheduleForDay($day)
    {
        if (!isset($this->schedules[$day])) {
            return [];
        }
        
        return $this->schedules[$day];
    }

    public function isAvailableOnDay($day)
    {
        return isset($this->schedules[$day]) && !empty($this->schedules[$day]);
    }

    // New methods for booking restrictions and fee calculations
    public function getMinimumBookingDays()
    {
        return $this->booking_restrictions['minimum_days'] ?? 1;
    }

    public function getMaximumBookingDays()
    {
        return $this->booking_restrictions['maximum_days'] ?? 365;
    }

    public function canBookForDate($requestedDate)
    {
        $today = now()->startOfDay();
        $requested = \Carbon\Carbon::parse($requestedDate)->startOfDay();
        $daysDifference = $today->diffInDays($requested, false);

        $minimumDays = $this->getMinimumBookingDays();
        $maximumDays = $this->getMaximumBookingDays();

        return $daysDifference >= $minimumDays && $daysDifference <= $maximumDays;
    }

    public function getFeeForDate($requestedDate)
    {
        if (empty($this->fees)) {
            return null;
        }

        $today = now()->startOfDay();
        $requested = \Carbon\Carbon::parse($requestedDate)->startOfDay();
        $daysDifference = $today->diffInDays($requested, false);

        // Check fee conditions in order of priority
        foreach ($this->fees as $feeType => $feeData) {
            if (is_array($feeData) && isset($feeData['condition'])) {
                $condition = $feeData['condition'];
                
                // Check if condition is met
                if ($this->checkFeeCondition($condition, $daysDifference)) {
                    return [
                        'type' => $feeType,
                        'amount' => $feeData['amount'],
                        'description' => $feeData['description'] ?? $feeType
                    ];
                }
            } else {
                // Simple fee structure (fallback)
                return [
                    'type' => $feeType,
                    'amount' => $feeData,
                    'description' => $feeType
                ];
            }
        }

        // Return the first fee as default
        $firstFee = array_values($this->fees)[0];
        return [
            'type' => array_keys($this->fees)[0],
            'amount' => is_array($firstFee) ? $firstFee['amount'] : $firstFee,
            'description' => 'Regular'
        ];
    }

    private function checkFeeCondition($condition, $daysDifference)
    {
        if (isset($condition['max_days'])) {
            return $daysDifference <= $condition['max_days'];
        }
        
        if (isset($condition['min_days']) && isset($condition['max_days'])) {
            return $daysDifference >= $condition['min_days'] && $daysDifference <= $condition['max_days'];
        }

        return true; // Default condition
    }

    public function getBookingRestrictionMessage()
    {
        $minimumDays = $this->getMinimumBookingDays();
        $maximumDays = $this->getMaximumBookingDays();

        if ($minimumDays == 1 && $maximumDays == 365) {
            return "Bookings can be made up to 1 year in advance.";
        }

        $message = "Bookings must be made ";
        
        if ($minimumDays > 1) {
            $message .= "at least {$minimumDays} days in advance";
        }
        
        if ($maximumDays < 365) {
            if ($minimumDays > 1) {
                $message .= " and ";
            }
            $message .= "up to {$maximumDays} days in advance";
        }

        return $message . ".";
    }

    /**
     * Get all bookings for this service
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
} 