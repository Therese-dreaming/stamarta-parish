<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MinistryActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',
        'title',
        'description',
        'start_at',
        'end_at',
        'is_all_day',
        'location',
        'is_public',
        'estimated_budget',
        'budget_breakdown',
        'has_budget_request',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_all_day' => 'boolean',
        'is_public' => 'boolean',
        'estimated_budget' => 'decimal:2',
        'budget_breakdown' => 'array',
    ];

    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    public function budgetRequests(): HasMany
    {
        return $this->hasMany(MinistryBudgetRequest::class, 'activity_id');
    }

    public function pendingBudgetRequest()
    {
        return $this->hasOne(MinistryBudgetRequest::class, 'activity_id')->where('status', 'pending');
    }

    public function approvedBudgetRequest()
    {
        return $this->hasOne(MinistryBudgetRequest::class, 'activity_id')->where('status', 'approved');
    }

    // Accessors for backward compatibility
    public function getBudgetStatusAttribute()
    {
        if ($this->approvedBudgetRequest) {
            return 'approved';
        } elseif ($this->pendingBudgetRequest) {
            return 'pending';
        } elseif ($this->estimated_budget > 0) {
            return 'planned';
        } else {
            return 'none';
        }
    }

    public function getBudgetStatusTextAttribute()
    {
        return match($this->budget_status) {
            'approved' => 'Budget Approved',
            'pending' => 'Budget Pending',
            'planned' => 'Budget Planned',
            default => 'No Budget'
        };
    }

    public function getBudgetStatusColorAttribute()
    {
        return match($this->budget_status) {
            'approved' => 'green',
            'pending' => 'yellow',
            'planned' => 'blue',
            default => 'gray'
        };
    }

    public function getIsActivityBasedAttribute()
    {
        return !is_null($this->budgetRequests()->first());
    }

    public function getActivityTitleAttribute()
    {
        return $this->title;
    }

    /**
     * Get the estimated budget as a float value
     */
    public function getEstimatedBudgetNumericAttribute()
    {
        return (float)$this->estimated_budget;
    }

    /**
     * Check if this ministry activity conflicts with a specific date and time
     */
    public function conflictsWith(string $date, string $time): bool
    {
        $bookingDateTime = Carbon::parse($date . ' ' . $time);
        $activityStart = $this->start_at;
        $activityEnd = $this->end_at ?: $this->start_at->copy()->addHours(2); // Default 2 hours if no end time

        // Check if the booking time falls within the activity time range
        return $bookingDateTime->between($activityStart, $activityEnd) ||
               $activityStart->between($bookingDateTime, $bookingDateTime->copy()->addMinutes(60)) ||
               $activityEnd->between($bookingDateTime, $bookingDateTime->copy()->addMinutes(60));
    }

    /**
     * Check if this ministry activity conflicts with another ministry activity
     */
    public function conflictsWithActivity(MinistryActivity $otherActivity): bool
    {
        $thisStart = $this->start_at;
        $thisEnd = $this->end_at ?: $this->start_at->copy()->addHours(2);
        $otherStart = $otherActivity->start_at;
        $otherEnd = $otherActivity->end_at ?: $otherActivity->start_at->copy()->addHours(2);

        // Check if time ranges overlap
        return $thisStart->lt($otherEnd) && $thisEnd->gt($otherStart);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // When a ministry activity is deleted, delete associated budget requests and files
        static::deleting(function ($activity) {
            // Get all budget requests for this activity
            $budgetRequests = $activity->budgetRequests;
            
            foreach ($budgetRequests as $budgetRequest) {
                // Delete associated files from storage
                $files = $budgetRequest->files;
                foreach ($files as $file) {
                    // Delete physical file from storage
                    if (Storage::disk('public')->exists($file->path)) {
                        Storage::disk('public')->delete($file->path);
                    }
                    // Delete file record
                    $file->delete();
                }
                
                // Delete the budget request (this will cascade to delete files due to foreign key)
                $budgetRequest->delete();
            }
        });
    }
}


