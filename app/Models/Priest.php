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
        'is_active',
        'specializations',
        'bio',
        'photo_path',
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
        'years_of_service',
    ];

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getYearsOfServiceAttribute()
    {
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
} 