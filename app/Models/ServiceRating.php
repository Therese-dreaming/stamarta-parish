<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'booking_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get the user who made the rating
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service being rated
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the booking associated with the rating
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the star rating as HTML
     */
    public function getStarsHtmlAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                $stars .= '<i class="far fa-star text-gray-300"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get the star rating as text
     */
    public function getStarsTextAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
} 