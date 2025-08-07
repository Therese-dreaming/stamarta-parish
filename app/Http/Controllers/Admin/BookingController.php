<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Priest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $bookings = Booking::with(['user', 'service', 'priest', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'acknowledged' => Booking::where('status', 'acknowledged')->count(),
            'payment_hold' => Booking::where('status', 'payment_hold')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function calendar()
    {
        $bookings = Booking::with(['user', 'service', 'payment'])
            ->get();

        $parochialActivities = \App\Models\ParochialActivity::active()
            ->get();

        $calendarEvents = [];

        // Add bookings to calendar events
        foreach ($bookings as $booking) {
            // Use service_date if available, otherwise use created_at
            $eventDate = $booking->service_date ?? $booking->created_at->format('Y-m-d');
            $eventTime = $booking->service_time ?? '09:00:00';
            
            $calendarEvents[] = [
                'id' => 'booking-' . $booking->id,
                'title' => 'Booking #' . $booking->id . ' - ' . ($booking->service->name ?? 'Unknown Service'),
                'start' => $eventDate . 'T' . $eventTime,
                'end' => $eventDate . 'T' . $eventTime,
                'type' => 'booking',
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'backgroundColor' => $this->getStatusColor($booking->status),
                'borderColor' => $this->getStatusColor($booking->status),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'user_name' => $booking->user->name ?? 'Unknown User',
                    'service_name' => $booking->service->name ?? 'Unknown Service',
                    'contact_phone' => $booking->contact_phone ?? 'No phone',
                    'status' => $booking->status,
                    'service_date' => $booking->service_date,
                    'service_time' => $booking->service_time,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                ]
            ];
        }

        // Add parochial activities to calendar events
        foreach ($parochialActivities as $activity) {
            if ($activity->is_recurring) {
                // For recurring activities, add multiple events
                $affectedDates = $activity->getAffectedDates();
                foreach ($affectedDates as $date) {
                    $calendarEvents[] = [
                        'id' => 'activity-' . $activity->id . '-' . $date->format('Y-m-d'),
                        'title' => $activity->title,
                        'start' => $date->format('Y-m-d') . 'T' . $activity->start_time->format('H:i:s'),
                        'end' => $date->format('Y-m-d') . 'T' . $activity->end_time->format('H:i:s'),
                        'type' => 'activity',
                        'activity_id' => $activity->id,
                        'backgroundColor' => '#fbbf24', // Yellow for activities
                        'borderColor' => '#f59e0b',
                        'textColor' => '#ffffff',
                        'extendedProps' => [
                            'description' => $activity->description,
                            'location' => $activity->location,
                            'organizer' => $activity->organizer,
                            'block_type' => $activity->block_type,
                            'is_recurring' => true,
                        ]
                    ];
                }
            } else {
                // For single events
                $calendarEvents[] = [
                    'id' => 'activity-' . $activity->id,
                    'title' => $activity->title,
                    'start' => $activity->event_date->format('Y-m-d') . 'T' . $activity->start_time->format('H:i:s'),
                    'end' => $activity->event_date->format('Y-m-d') . 'T' . $activity->end_time->format('H:i:s'),
                    'type' => 'activity',
                    'activity_id' => $activity->id,
                    'backgroundColor' => '#fbbf24', // Yellow for activities
                    'borderColor' => '#f59e0b',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'description' => $activity->description,
                        'location' => $activity->location,
                        'organizer' => $activity->organizer,
                        'block_type' => $activity->block_type,
                        'is_recurring' => false,
                    ]
                ];
            }
        }

        return view('admin.bookings.calendar', compact('calendarEvents'));
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => '#fbbf24', // Yellow
            'acknowledged' => '#3b82f6', // Blue
            'payment_hold' => '#f97316', // Orange
            'approved' => '#10b981', // Green
            'rejected' => '#ef4444', // Red
            'completed' => '#059669', // Dark Green
            default => '#6b7280', // Gray
        };
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'service', 'priest', 'payment', 'actions.performedBy', 'actions.priest']);
        $priests = Priest::where('is_active', true)->get();
        
        return view('admin.bookings.show', compact('booking', 'priests'));
    }

    public function acknowledge(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be acknowledged.');
        }

        $request->validate([
            'total_fee' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking->update(['status' => 'acknowledged']);

        // Create or update payment record with total fee
        $booking->payment()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'total_fee' => $request->total_fee,
                'payment_status' => 'pending',
            ]
        );

        // Create booking action
        $booking->actions()->create([
            'action_type' => 'acknowledged',
            'notes' => $request->notes,
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Booking acknowledged successfully. Payment fee set to ₱' . number_format($request->total_fee, 2));
    }

    public function verifyPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'verification_status' => 'required|in:approved,rejected',
            'priest_id' => 'required_if:verification_status,approved|exists:priests,id',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($booking->status !== 'payment_hold') {
            return back()->with('error', 'Only bookings on payment hold can be verified.');
        }

        $status = $request->verification_status === 'approved' ? 'approved' : 'rejected';
        
        $booking->update([
            'status' => $status,
            'priest_id' => $request->verification_status === 'approved' ? $request->priest_id : null,
        ]);

        // Update payment status
        if ($booking->payment) {
            $booking->payment->update([
                'payment_status' => $request->verification_status === 'approved' ? 'verified' : 'rejected',
                'payment_verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);
        }

        // Create booking action
        $booking->actions()->create([
            'action_type' => $request->verification_status === 'approved' ? 'approved' : 'rejected',
            'notes' => $request->notes,
            'performed_by' => auth()->id(),
            'priest_id' => $request->verification_status === 'approved' ? $request->priest_id : null,
        ]);

        $message = $request->verification_status === 'approved' 
            ? 'Payment verified and booking approved successfully.' 
            : 'Payment rejected successfully.';

        return back()->with('success', $message);
    }

    public function complete(Request $request, Booking $booking)
    {
        if ($booking->status !== 'approved') {
            return back()->with('error', 'Only approved bookings can be marked as completed.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $booking->update(['status' => 'completed']);

        // Create booking action
        $booking->actions()->create([
            'action_type' => 'completed',
            'notes' => $request->notes,
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Booking marked as completed successfully.');
    }

    public function reject(Request $request, Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'acknowledged'])) {
            return back()->with('error', 'This booking cannot be rejected.');
        }

        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $booking->update(['status' => 'rejected']);

        // Create booking action
        $booking->actions()->create([
            'action_type' => 'rejected',
            'notes' => $request->notes,
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Booking rejected successfully.');
    }

    public function downloadDocument(Booking $booking, $documentType)
    {
        if (!isset($booking->requirements_submitted[$documentType])) {
            return back()->with('error', 'Document not found.');
        }

        $filePath = $booking->requirements_submitted[$documentType];
        
        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($filePath);
    }

    public function downloadPaymentProof(Booking $booking)
    {
        if (!$booking->payment || !$booking->payment->payment_proof) {
            return back()->with('error', 'Payment proof not found.');
        }

        if (!Storage::disk('public')->exists($booking->payment->payment_proof)) {
            return back()->with('error', 'Payment proof file not found.');
        }

        return Storage::disk('public')->download($booking->payment->payment_proof);
    }


} 