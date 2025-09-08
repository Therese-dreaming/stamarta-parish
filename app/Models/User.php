<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'date_of_birth',
        'email_verification_token',
        'email_verified_at',
        'password_reset_token',
        'password_reset_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'created_by');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function priest()
    {
        return $this->hasOne(Priest::class);
    }

    // Role methods
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isPriest()
    {
        return $this->hasRole('priest');
    }

    public function isMinistryHead()
    {
        return $this->hasRole('ministry_head');
    }

    public function isStaff()
    {
        return $this->hasRole('staff');
    }

    public function isUser()
    {
        return $this->hasRole('user');
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }

    /**
     * Get the user's age based on date of birth
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        return $this->date_of_birth->age;
    }

    /**
     * Check if user is of legal age (18 or older)
     */
    public function isOfLegalAge()
    {
        return $this->age !== null && $this->age >= 18;
    }

    /**
     * Check if user can book wedding (18+ and no existing wedding booking)
     */
    public function canBookWedding()
    {
        // Check if user is of legal age
        if (!$this->isOfLegalAge()) {
            return false;
        }

        // Check if user has any existing wedding booking (pending or completed)
        $weddingServiceIds = \App\Models\Service::where('service_type', 'wedding')
            ->orWhere('name', 'like', '%wedding%')
            ->orWhere('name', 'like', '%marriage%')
            ->pluck('id');

        if ($weddingServiceIds->isEmpty()) {
            return true; // No wedding services defined, allow booking
        }

        $existingWeddingBooking = $this->bookings()
            ->whereIn('service_id', $weddingServiceIds)
            ->whereIn('status', [
                Booking::STATUS_PENDING,
                Booking::STATUS_ACKNOWLEDGED,
                Booking::STATUS_PAYMENT_HOLD,
                Booking::STATUS_APPROVED,
                Booking::STATUS_COMPLETED
            ])
            ->exists();

        return !$existingWeddingBooking;
    }

    /**
     * Get existing wedding booking if any
     */
    public function getWeddingBooking()
    {
        $weddingServiceIds = \App\Models\Service::where('service_type', 'wedding')
            ->orWhere('name', 'like', '%wedding%')
            ->orWhere('name', 'like', '%marriage%')
            ->pluck('id');

        if ($weddingServiceIds->isEmpty()) {
            return null;
        }

        return $this->bookings()
            ->whereIn('service_id', $weddingServiceIds)
            ->whereIn('status', [
                Booking::STATUS_PENDING,
                Booking::STATUS_ACKNOWLEDGED,
                Booking::STATUS_PAYMENT_HOLD,
                Booking::STATUS_APPROVED,
                Booking::STATUS_COMPLETED
            ])
            ->first();
    }
}
