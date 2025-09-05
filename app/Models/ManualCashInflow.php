<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManualCashInflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',
        'amount',
        'source_type',
        'description',
        'source_details',
        'other_source_specify',
        'reference_no',
        'notes',
        'entered_by_user_id',
        'approved_by_user_id',
        'approved_at',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // Source type constants
    const SOURCE_DIOCESE = 'diocese';
    const SOURCE_DONATION = 'donation';
    const SOURCE_FUNDRAISING = 'fundraising';
    const SOURCE_EVENT_REVENUE = 'event_revenue';
    const SOURCE_MEMBERSHIP_FEE = 'membership_fee';
    const SOURCE_SPONSORSHIP = 'sponsorship';
    const SOURCE_OTHER = 'other';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    // Alias relationships for backward compatibility
    public function requestedBy()
    {
        return $this->enteredBy();
    }

    public function rejectedBy()
    {
        return null; // This field doesn't exist in the current schema
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeBySourceType($query, $sourceType)
    {
        return $query->where('source_type', $sourceType);
    }

    public function scopeByMinistry($query, $ministryId)
    {
        return $query->where('ministry_id', $ministryId);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBeApproved()
    {
        return $this->isPending();
    }

    public function canBeRejected()
    {
        return $this->isPending();
    }

    public function getSourceTypeLabel()
    {
        return ucfirst(str_replace('_', ' ', $this->source_type));
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getSourceTypeBadgeClass()
    {
        return match($this->source_type) {
            self::SOURCE_DIOCESE => 'bg-blue-100 text-blue-800',
            self::SOURCE_DONATION => 'bg-purple-100 text-purple-800',
            self::SOURCE_FUNDRAISING => 'bg-orange-100 text-orange-800',
            self::SOURCE_EVENT_REVENUE => 'bg-pink-100 text-pink-800',
            self::SOURCE_MEMBERSHIP_FEE => 'bg-indigo-100 text-indigo-800',
            self::SOURCE_SPONSORSHIP => 'bg-teal-100 text-teal-800',
            self::SOURCE_OTHER => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Accessor for date_received (since it doesn't exist in DB, use created_at)
    public function getDateReceivedAttribute()
    {
        return $this->created_at;
    }

    // Accessor for rejection_reason (since it doesn't exist in DB)
    public function getRejectionReasonAttribute()
    {
        return null;
    }

    // Accessor for rejected_at (since it doesn't exist in DB)
    public function getRejectedAtAttribute()
    {
        return null;
    }
}