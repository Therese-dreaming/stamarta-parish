<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',                    // Links booking to the user who made the request, used for authentication and user-specific booking views
        'service_id',                 // References the specific church service (baptism, wedding, etc.) being booked
        'ministry_id',                // Associates booking with a ministry for organizational tracking and fund management
        'service_date',               // Scheduled date for the church service, used for calendar views and scheduling conflicts
        'service_time',               // Specific time slot for the service, displayed in user-friendly format (e.g., "2:00 PM")
        'contact_phone',              // User's phone number for communication and contact verification
        'contact_address',            // User's address for service delivery and location verification
        'additional_notes',           // General notes from the user about special requests or requirements
        'requirements_submitted',     // JSON array storing file paths of uploaded documents (birth certificates, etc.)
        'additional_requirements',    // Extra requirements or notes from admin/staff during booking processing
        'custom_data',                // JSON object storing dynamic form fields specific to each service type
        'status',                     // Booking workflow status (pending, acknowledged, approved, completed, etc.)
        'priest_id',                  // Assigned priest for the service, used for priest-specific booking management
        'certificate_path',           // File path to generated certificate PDF after service completion
    ];

    protected $casts = [
        'service_date' => 'date',
        'requirements_submitted' => 'array',
        'custom_data' => 'array',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACKNOWLEDGED = 'acknowledged';
    const STATUS_PAYMENT_HOLD = 'payment_hold';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function priest()
    {
        return $this->belongsTo(Priest::class);
    }

    public function payment()
    {
        return $this->hasOne(BookingPayment::class);
    }

    public function actions()
    {
        return $this->hasMany(BookingAction::class);
    }

    public function latestAction()
    {
        return $this->hasOne(BookingAction::class)->latest();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAcknowledged($query)
    {
        return $query->where('status', self::STATUS_ACKNOWLEDGED);
    }

    public function scopePaymentHold($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_HOLD);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_ACKNOWLEDGED => 'bg-blue-100 text-blue-800',
            self::STATUS_PAYMENT_HOLD => 'bg-orange-100 text-orange-800',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        if (!$this->payment) {
            return 'bg-gray-100 text-gray-800';
        }
        return $this->payment->payment_status_badge;
    }

    public function getFormattedDateAttribute()
    {
        if (!$this->service_date) {
            return 'No date set';
        }
        
        try {
            return $this->service_date->format('F d, Y');
        } catch (\Exception $e) {
            return 'Invalid date';
        }
    }

    public function getFormattedTimeAttribute()
    {
        if (!$this->service_time) {
            return 'No time set';
        }
        
        // If service_time is already in the correct format (e.g., "2:00 PM"), return it
        if (preg_match('/^\d{1,2}:\d{2}\s?(AM|PM)$/i', $this->service_time)) {
            return $this->service_time;
        }
        
        // If it's a datetime string, format it
        try {
            $time = \Carbon\Carbon::parse($this->service_time);
            return $time->format('g:i A');
        } catch (\Exception $e) {
            return $this->service_time;
        }
    }

    public function getFormattedTotalFeeAttribute()
    {
        if (!$this->payment) {
            return 'Contact office';
        }
        
        // If payment exists but total_fee is null/empty, show service fee as fallback
        if ($this->payment->total_fee === null || $this->payment->total_fee === '') {
            if ($this->service && $this->service->fees) {
                $feeInfo = $this->service->getFeeForDate($this->service_date);
                $feeAmount = is_array($feeInfo) ? ($feeInfo['amount'] ?? 0) : 0;
                if (is_numeric($feeAmount) && $feeAmount > 0) {
                    return '₱' . number_format((float)$feeAmount, 2);
                }
            }
            return 'Contact office';
        }
        
        return $this->payment->formatted_total_fee;
    }
} 