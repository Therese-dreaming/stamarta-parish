<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Priest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'birth_date',
        'ordination_date',
        'years_of_service',
        'is_active',
        'leave_status',
        'specializations',
        'bio',
        'photo_path',
        'user_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'ordination_date' => 'date',
        'is_active' => 'boolean',
        'specializations' => 'array',
    ];

    protected $appends = [
        'full_name',
        'age',
        'calculated_years_of_service',
    ];

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getCalculatedYearsOfServiceAttribute()
    {
        // If manually set years_of_service, use that, otherwise calculate from ordination_date
        if ($this->attributes['years_of_service']) {
            return $this->attributes['years_of_service'];
        }
        return $this->ordination_date ? $this->ordination_date->diffInYears(now()) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaves()
    {
        return $this->hasMany(\App\Models\PriestLeave::class);
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    // Leave management methods
    public function isOnLeave()
    {
        return $this->leave_status !== 'active';
    }

    public function isAvailable()
    {
        return $this->is_active && $this->leave_status === 'active';
    }

    public function getLeaveStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'bg-green-100 text-green-800',
            'on_leave' => 'bg-yellow-100 text-yellow-800',
            'pilgrimage' => 'bg-blue-100 text-blue-800',
            'sabbatical' => 'bg-purple-100 text-purple-800',
            'retired' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->leave_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getLeaveStatusLabelAttribute()
    {
        $labels = [
            'active' => 'Active',
            'on_leave' => 'On Leave',
            'pilgrimage' => 'Pilgrimage',
            'sabbatical' => 'Sabbatical',
            'retired' => 'Retired',
        ];

        return $labels[$this->leave_status] ?? 'Unknown';
    }
} 