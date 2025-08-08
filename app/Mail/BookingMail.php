<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $type)
    {
        $this->booking = $booking;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'confirmation' => 'Booking Confirmation - Sta. Marta Parish',
            'payment_instructions' => 'Payment Instructions - Sta. Marta Parish',
            'payment_received' => 'Payment Received - Sta. Marta Parish',
            'approved' => 'Booking Approved - Sta. Marta Parish',
            'rejected' => 'Booking Update - Sta. Marta Parish',
            default => 'Booking Update - Sta. Marta Parish'
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = match($this->type) {
            'confirmation' => 'emails.booking-confirmation',
            'payment_instructions' => 'emails.payment-instructions',
            'payment_received' => 'emails.payment-received',
            'approved' => 'emails.booking-approved',
            'rejected' => 'emails.booking-rejected',
            default => 'emails.booking-confirmation'
        };

        return new Content(
            view: $view,
            with: [
                'booking' => $this->booking,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
} 