<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'total_fee',
        'payment_method',
        'payment_reference',
        'payment_proof',
        'payment_notes',
        'payment_status',
        'payment_submitted_at',
        'payment_verified_at',
        'verified_by',
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
        if (!$fee || !is_numeric($fee)) {
            return '₱0.00';
        }
        return '₱' . number_format($fee, 2);
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