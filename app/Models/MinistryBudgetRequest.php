<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class MinistryBudgetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',          // Links budget request to specific ministry, used for organization and access control
        'activity_id',          // Optional link to ministry activity, used for activity-based budget requests
        'purpose',              // Brief description of budget purpose, displayed in lists and approval views
        'details',              // Detailed explanation of budget request, used for comprehensive documentation
        'status',               // Request workflow status (pending, approved, rejected, complete), determines UI display and permissions
        'requested_by_user_id', // User who submitted the request, used for audit trail and accountability
        'approved_by_user_id',  // Admin who approved/rejected the request, used for approval workflow tracking
        'approved_at',          // Timestamp when request was processed, used for audit trail and reporting
        'rejection_notes',      // Optional notes explaining rejection reason, used for feedback and documentation
        'completed_at',         // Timestamp when activity was marked as complete, used for completion tracking
        'completion_notes',     // Optional notes about activity completion, used for final documentation
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function activity()
    {
        return $this->belongsTo(MinistryActivity::class, 'activity_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function files()
    {
        return $this->hasMany(MinistryBudgetRequestFile::class, 'budget_request_id');
    }

    public function fundTransactions()
    {
        return $this->hasMany(MinistryFundTransaction::class, 'budget_request_id');
    }

    /**
     * Get the amount from the associated activity's estimated budget
     */
    public function getAmountAttribute()
    {
        return $this->activity ? $this->activity->estimated_budget : 0;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'green',
            'complete' => 'blue',
            'pending' => 'yellow',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function getStatusTextAttribute()
    {
        return ucfirst($this->status);
    }

    public function getIsActivityBasedAttribute()
    {
        return !is_null($this->activity_id);
    }

    public function getActivityTitleAttribute()
    {
        return $this->activity ? $this->activity->title : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($budgetRequest) {
            $budgetRequest->fundTransactions()->delete();
        });
    }
}


