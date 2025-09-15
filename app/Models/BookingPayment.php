<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',            // Links payment to specific booking, used for one-to-one relationship with booking records
        'total_fee',             // Total amount to be paid for the service, set by admin during acknowledgment and displayed in payment views
        'payment_method',        // Payment method used (gcash, metrobank), determines UI display and processing workflow
        'payment_reference',     // Transaction reference number provided by user, used for payment verification and tracking
        'payment_proof',         // File path to uploaded payment proof document, used for verification and download functionality
        'payment_notes',         // Optional notes from user about payment, displayed in admin views for additional context
        'payment_status',        // Payment workflow status (pending, paid, verified, rejected), determines UI badges and workflow progression
        'payment_submitted_at',  // Timestamp when user submitted payment proof, used for tracking and display in timeline views
        'payment_verified_at',   // Timestamp when admin verified payment, used for audit trail and completion tracking
        'verified_by',           // User ID who verified the payment, used for accountability and audit trail purposes
    ];

    protected $casts = [
        'total_fee' => 'decimal:2',
        'payment_submitted_at' => 'datetime',
        'payment_verified_at' => 'datetime',
    ];

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_VERIFIED = 'verified';
    const PAYMENT_REJECTED = 'rejected';

    // Payment method constants
    const METHOD_GCASH = 'gcash';
    const METHOD_METROBANK = 'metrobank';

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFormattedTotalFeeAttribute()
    {
        $fee = $this->total_fee;
        if ($fee === null || $fee === '' || !is_numeric($fee)) {
            return 'Contact office';
        }
        return '₱' . number_format((float)$fee, 2);
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            self::PAYMENT_PENDING => 'bg-yellow-100 text-yellow-800',
            self::PAYMENT_PAID => 'bg-blue-100 text-blue-800',
            self::PAYMENT_VERIFIED => 'bg-green-100 text-green-800',
            self::PAYMENT_REJECTED => 'bg-red-100 text-red-800',
        ];

        $status = $this->payment_status;
        if (!$status || !is_string($status)) {
            return 'bg-gray-100 text-gray-800';
        }

        return $badges[$status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPaymentMethodLabelAttribute()
    {
        $method = $this->payment_method;
        if (!$method || !is_string($method)) {
            return 'Unknown';
        }

        return match($method) {
            self::METHOD_GCASH => 'GCash',
            self::METHOD_METROBANK => 'Metrobank',
            default => 'Unknown',
        };
    }
} 