<?php

namespace App\Services;

use App\Models\Booking;
use App\Mail\BookingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send booking confirmation email
     */
    public static function sendBookingConfirmation(Booking $booking): bool
    {
        try {
            Mail::to($booking->user->email)->send(new BookingMail($booking, 'confirmation'));
            Log::info('Booking confirmation email sent', ['booking_id' => $booking->id, 'user_email' => $booking->user->email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'user_email' => $booking->user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send payment instructions email
     */
    public static function sendPaymentInstructions(Booking $booking): bool
    {
        try {
            Mail::to($booking->user->email)->send(new BookingMail($booking, 'payment_instructions'));
            Log::info('Payment instructions email sent', ['booking_id' => $booking->id, 'user_email' => $booking->user->email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment instructions email', [
                'booking_id' => $booking->id,
                'user_email' => $booking->user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send payment received confirmation email
     */
    public static function sendPaymentReceived(Booking $booking): bool
    {
        try {
            Mail::to($booking->user->email)->send(new BookingMail($booking, 'payment_received'));
            Log::info('Payment received email sent', ['booking_id' => $booking->id, 'user_email' => $booking->user->email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment received email', [
                'booking_id' => $booking->id,
                'user_email' => $booking->user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send booking approval email
     */
    public static function sendBookingApproved(Booking $booking): bool
    {
        try {
            Mail::to($booking->user->email)->send(new BookingMail($booking, 'approved'));
            Log::info('Booking approved email sent', ['booking_id' => $booking->id, 'user_email' => $booking->user->email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send booking approved email', [
                'booking_id' => $booking->id,
                'user_email' => $booking->user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send booking rejection email
     */
    public static function sendBookingRejected(Booking $booking): bool
    {
        try {
            Mail::to($booking->user->email)->send(new BookingMail($booking, 'rejected'));
            Log::info('Booking rejected email sent', ['booking_id' => $booking->id, 'user_email' => $booking->user->email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send booking rejected email', [
                'booking_id' => $booking->id,
                'user_email' => $booking->user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
} 