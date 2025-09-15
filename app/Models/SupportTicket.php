<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',              // Links ticket to specific user, used for user-specific ticket tracking and authentication
		'name',                 // Contact name for the ticket, used for identification and communication
		'email',                // Contact email for the ticket, used for communication and notifications
		'subject',              // Ticket subject line, used for identification and categorization
		'message',              // Detailed ticket message/description, used for issue documentation and resolution
		'status',               // Ticket status (open, closed, pending, etc.), used for workflow management and filtering
	];
} 