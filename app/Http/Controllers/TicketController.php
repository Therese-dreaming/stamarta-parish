<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SupportTicketMail;
use App\Models\SupportTicket;

class TicketController extends Controller
{
	public function create()
	{
		$parishEmail = env('PARISH_SUPPORT_EMAIL', config('mail.from.address'));
		return view('tickets.create', [
			'parishEmail' => $parishEmail,
		]);
	}

	public function store(Request $request)
	{
		$baseRules = [
			'subject' => 'required|string|max:255',
			'message' => 'required|string|max:5000',
		];

		if (!Auth::check()) {
			$baseRules = array_merge($baseRules, [
				'name' => 'required|string|max:255',
				'email' => 'required|email',
			]);
		}

		$validated = $request->validate($baseRules);

		$name = Auth::check() ? Auth::user()->name : $validated['name'];
		$emailAddress = Auth::check() ? Auth::user()->email : $validated['email'];
		$subjectLine = $validated['subject'];
		$messageBody = $validated['message'];

		$ticket = SupportTicket::create([
			'user_id' => Auth::id(),
			'name' => $name,
			'email' => $emailAddress,
			'subject' => $subjectLine,
			'message' => $messageBody,
			'status' => 'open',
		]);

		$parishEmail = env('PARISH_SUPPORT_EMAIL', config('mail.from.address'));
		try {
			Mail::to($parishEmail)
				->send(new SupportTicketMail($ticket));
			return redirect()->route('tickets.create')->with('status', 'Your ticket has been sent. We will get back to you soon.');
		} catch (\Exception $e) {
			Log::error('Failed to send support ticket email', [
				'error' => $e->getMessage(),
				'ticket_id' => $ticket->id ?? null,
			]);
			return back()
				->with('error', 'We could not send your ticket right now. Please try again later.')
				->withErrors(['email' => 'Delivery failed: ' . $e->getMessage()]);
		}
	}
} 