<?php

namespace App\Http\Controllers;

use App\Models\ServiceRating;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRatingController extends Controller
{
    /**
     * Store a new rating
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'service_id' => 'required|exists:services,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        // Check if user owns the booking
        $booking = Booking::where('id', $request->booking_id)
                         ->where('user_id', Auth::id())
                         ->where('status', 'completed')
                         ->firstOrFail();

        // Check if user already rated this booking
        $existingRating = ServiceRating::where('user_id', Auth::id())
                                     ->where('booking_id', $request->booking_id)
                                     ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this service for this booking.'
            ], 400);
        }

        // Create the rating
        $rating = ServiceRating::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'rating' => $rating
        ]);
    }

    /**
     * Update an existing rating
     */
    public function update(Request $request, ServiceRating $rating)
    {
        // Check if user owns the rating
        if ($rating->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $rating->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating updated successfully!',
            'rating' => $rating
        ]);
    }

    /**
     * Get rating for a specific booking
     */
    public function getRating(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id'
        ]);

        $rating = ServiceRating::where('user_id', Auth::id())
                              ->where('booking_id', $request->booking_id)
                              ->first();

        return response()->json([
            'success' => true,
            'rating' => $rating
        ]);
    }
} 