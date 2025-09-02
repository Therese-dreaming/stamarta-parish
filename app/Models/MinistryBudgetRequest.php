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
}


