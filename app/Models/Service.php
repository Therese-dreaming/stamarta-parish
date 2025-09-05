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
        'ministry_id',
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
        $preferredOrder = ['regular', 'rush'];
        $fees = $this->fees;
        
        // Build an ordered list: preferred keys first in order, then the rest alphabetically
        $orderedKeys = [];
        foreach ($preferredOrder as $key) {
            if (array_key_exists($key, $fees)) {
                $orderedKeys[] = $key;
            }
        }
        $remainingKeys = array_diff(array_keys($fees), $orderedKeys);
        sort($remainingKeys);
        $orderedKeys = array_merge($orderedKeys, $remainingKeys);
        
        foreach ($orderedKeys as $type) {
            $feeData = $fees[$type];
            if (is_array($feeData) && isset($feeData['amount'])) {
                $label = $feeData['description'] ?? ucfirst($type);
                $formatted[] = $label . ': ₱' . number_format($feeData['amount'], 2);
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

    /**
     * Get all ratings for this service
     */
    public function ratings()
    {
        return $this->hasMany(ServiceRating::class);
    }

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    /**
     * Get the average rating for this service
     */
    public function getAverageRatingAttribute()
    {
        $rating = $this->ratings()->avg('rating');
        return $rating ? round($rating, 1) : 0;
    }

    /**
     * Get the total number of ratings for this service
     */
    public function getTotalRatingsAttribute()
    {
        return $this->ratings()->count();
    }

    /**
     * Get the star rating HTML for display
     */
    public function getStarsHtmlAttribute()
    {
        $rating = $this->average_rating;
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<i class="fas fa-star text-yellow-400"></i>';
            } elseif ($i - $rating < 1) {
                $stars .= '<i class="fas fa-star-half-alt text-yellow-400"></i>';
            } else {
                $stars .= '<i class="far fa-star text-gray-300"></i>';
            }
        }
        
        return $stars;
    }

    /**
     * Check if a user has rated this service for a specific booking
     */
    public function hasUserRating($userId, $bookingId)
    {
        return $this->ratings()->where('user_id', $userId)->where('booking_id', $bookingId)->exists();
    }
} 