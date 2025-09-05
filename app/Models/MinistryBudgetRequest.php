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
        'ministry_id',
        'activity_id',
        'purpose',
        'details',
        'status',
        'requested_by_user_id',
        'approved_by_user_id',
        'approved_at',
        'rejection_notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
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


