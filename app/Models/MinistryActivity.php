<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_all_day' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function conflictsWith(string $date, string $time): bool
    {
        // If all-day and same date
        if ($this->is_all_day && $this->start_at->toDateString() === $date) {
            return true;
        }

        // If spans the given datetime
        $dt = \Carbon\Carbon::parse($date . ' ' . $time);
        $start = $this->start_at;
        $end = $this->end_at ?: $this->start_at; // if no end, treat as instant start

        return $dt->betweenIncluded($start, $end);
    }
}


