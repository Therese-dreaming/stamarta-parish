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
}
