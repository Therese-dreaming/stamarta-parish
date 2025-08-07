<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

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
        'total_fee',
        'payment_status',
        'payment_reference',
        'payment_proof',
        'payment_notes',
        'payment_submitted_at',
        'acknowledged_at',
        'acknowledged_by',

        'verified_at',
        'verified_by',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'completed_at',
        'completed_by',
        'cancelled_at',
        'notes',
    ];

    protected $casts = [
        'service_date' => 'date',
        'requirements_submitted' => 'array',
        'custom_data' => 'array',
        'total_fee' => 'decimal:2',
        'payment_submitted_at' => 'datetime',
        'acknowledged_at' => 'datetime',

        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACKNOWLEDGED = 'acknowledged';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_VERIFIED = 'verified';
    const PAYMENT_REJECTED = 'rejected';

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

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAcknowledged($query)
    {
        return $query->where('status', self::STATUS_ACKNOWLEDGED);
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
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            self::PAYMENT_PENDING => 'bg-yellow-100 text-yellow-800',
            self::PAYMENT_PAID => 'bg-blue-100 text-blue-800',
            self::PAYMENT_PARTIAL => 'bg-blue-100 text-blue-800',
            self::PAYMENT_VERIFIED => 'bg-green-100 text-green-800',
            self::PAYMENT_REJECTED => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getFormattedDateAttribute()
    {
        return $this->service_date->format('F d, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->service_time;
    }

    public function getFormattedTotalFeeAttribute()
    {
        return '₱' . number_format($this->total_fee, 2);
    }
} 