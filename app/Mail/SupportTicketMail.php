<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SupportTicket;

class SupportTicketMail extends Mailable
{
	use Queueable, SerializesModels;

	public SupportTicket $ticket;

	public function __construct(SupportTicket $ticket)
	{
		$this->ticket = $ticket;
	}

	public function build(): self
	{
		$subject = '[Support Ticket #' . $this->ticket->id . '] ' . $this->ticket->subject;
		return $this
			->subject($subject)
			->view('emails.support-ticket')
			->with([
				'ticket' => $this->ticket,
			]);
	}
} 