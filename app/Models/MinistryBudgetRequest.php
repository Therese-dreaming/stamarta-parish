<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryBudgetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',
        'activity_id',
        'amount',
        'purpose',
        'details',
        'status',
        'requested_by_user_id',
        'approved_by_user_id',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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
}


