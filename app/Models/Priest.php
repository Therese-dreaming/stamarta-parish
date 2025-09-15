<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Priest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',                 // Priest's full name, used for display and identification in lists and forms
        'email',                // Priest's email address, used for communication and system notifications
        'phone',                // Priest's phone number, used for contact and communication purposes
        'address',              // Priest's residential address, used for contact information and records
        'birth_date',           // Priest's date of birth, used for age calculation and personal records
        'ordination_date',      // Date when priest was ordained, used for years of service calculation and records
        'years_of_service',     // Manual override for years of service, used when automatic calculation is not desired
        'is_active',            // Boolean flag for priest availability, used for filtering active/inactive priests
        'leave_status',         // Current leave status (active, on_leave, pilgrimage, etc.), used for availability checking
        'specializations',      // JSON array of priest specializations, used for service assignment and filtering
        'bio',                  // Priest's biographical information, used for profile display and documentation
        'photo_path',           // File path to priest's photo, used for profile images and display
        'user_id',              // Optional link to user account, used for authentication and system integration
        'deleted_at',           // Timestamp when priest was soft deleted, used for soft delete functionality and data recovery
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