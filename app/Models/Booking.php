<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'service_date',
        'service_time',
        'contact_phone',
        'contact_address',
        'additional_notes',
        'requirements_submitted',
        'additional_requirements',
        'custom_data',
        'status',
        'priest_id',
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
        return $this->service_time ?? 'No time set';
    }

    public function getFormattedTotalFeeAttribute()
    {
        if (!$this->payment) {
            return 'Contact office';
        }
        return $this->payment->formatted_total_fee;
    }
} 