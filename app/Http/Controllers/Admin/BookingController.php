<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Priest;
use App\Models\PriestLeave;
use App\Services\EmailService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
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

        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.bookings.index', compact('bookings', 'stats', 'isStaff'));
    }

    public function calendar()
    {
        $bookings = Booking::with(['user', 'service', 'payment'])
            ->get();

        $parochialActivities = \App\Models\ParochialActivity::active()->get();
        $ministryActivities = \App\Models\MinistryActivity::query()->get();

        $calendarEvents = [];

        // Add bookings to calendar events
        foreach ($bookings as $booking) {
            // Use service_date if available, otherwise use created_at
            $eventDate = $booking->service_date ? 
                ($booking->service_date instanceof \Carbon\Carbon ? $booking->service_date->format('Y-m-d') : \Carbon\Carbon::parse($booking->service_date)->format('Y-m-d')) : 
                $booking->created_at->format('Y-m-d');
            
            $eventTime = $booking->service_time ? 
                ($booking->service_time instanceof \Carbon\Carbon ? $booking->service_time->format('H:i:s') : \Carbon\Carbon::parse($booking->service_time)->format('H:i:s')) : 
                '09:00:00';
            
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
                    'service_date' => $booking->service_date ? 
                        ($booking->service_date instanceof \Carbon\Carbon ? $booking->service_date->format('Y-m-d') : \Carbon\Carbon::parse($booking->service_date)->format('Y-m-d')) : 
                        null,
                    'service_time' => $booking->service_time ? 
                        ($booking->service_time instanceof \Carbon\Carbon ? $booking->service_time->format('H:i:s') : \Carbon\Carbon::parse($booking->service_time)->format('H:i:s')) : 
                        null,
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
                    // Ensure $date is a Carbon instance
                    $dateObj = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
                    
                    $calendarEvents[] = [
                        'id' => 'activity-' . $activity->id . '-' . $dateObj->format('Y-m-d'),
                        'title' => $activity->title,
                        'start' => $dateObj->format('Y-m-d') . 'T' . $activity->start_time->format('H:i:s'),
                        'end' => $dateObj->format('Y-m-d') . 'T' . $activity->end_time->format('H:i:s'),
                        'type' => 'activity',
                        'activity_id' => $activity->id,
                        'backgroundColor' => 'rgba(251, 191, 36, 0.25)', // Yellow with 25% opacity for activities
                        'borderColor' => 'rgba(251, 191, 36, 0.6)',
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
                    'backgroundColor' => 'rgba(251, 191, 36, 0.25)', // Yellow with 25% opacity for activities
                    'borderColor' => 'rgba(251, 191, 36, 0.6)',
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

        // Add ministry activities to calendar events
        foreach ($ministryActivities as $mAct) {
            $calendarEvents[] = [
                'id' => 'ministry-activity-' . $mAct->id,
                'title' => '[Ministry] ' . $mAct->title,
                'start' => $mAct->start_at->format('Y-m-d\TH:i:s'),
                'end' => $mAct->end_at ? $mAct->end_at->format('Y-m-d\TH:i:s') : null,
                'type' => 'ministry_activity',
                'activity_id' => $mAct->id,
                'backgroundColor' => 'rgba(96, 165, 250, 0.25)',
                'borderColor' => 'rgba(96, 165, 250, 0.6)',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'description' => $mAct->description,
                    'location' => $mAct->location,
                    'ministry' => optional($mAct->ministry)->name,
                    'is_all_day' => $mAct->is_all_day,
                    'is_public' => $mAct->is_public,
                ]
            ];
        }

        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.bookings.calendar', compact('calendarEvents', 'isStaff'));
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => 'rgba(251, 191, 36, 0.25)', // Yellow with 25% opacity
            'acknowledged' => 'rgba(59, 130, 246, 0.25)', // Blue with 25% opacity
            'payment_hold' => 'rgba(249, 115, 22, 0.25)', // Orange with 25% opacity
            'approved' => 'rgba(16, 185, 129, 0.25)', // Green with 25% opacity
            'rejected' => 'rgba(239, 68, 68, 0.25)', // Red with 25% opacity
            'completed' => 'rgba(5, 150, 105, 0.25)', // Dark Green with 25% opacity
            default => 'rgba(107, 114, 128, 0.25)', // Gray with 25% opacity
        };
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'service', 'priest', 'payment', 'actions.performedBy', 'actions.priest']);

        // Only include priests who are active and NOT on an approved leave that overlaps service_date
        $serviceDate = $booking->service_date ? ( $booking->service_date instanceof \Carbon\Carbon ? $booking->service_date->toDateString() : \Carbon\Carbon::parse($booking->service_date)->toDateString() ) : null;

        $priests = Priest::where('is_active', true)
            ->when($serviceDate, function($q) use ($serviceDate) {
                $q->whereDoesntHave('leaves', function($l) use ($serviceDate) {
                    $l->where('status', 'approved')
                      ->whereDate('start_date', '<=', $serviceDate)
                      ->whereDate('end_date', '>=', $serviceDate);
                });
            })
            ->get();
        
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.bookings.show', compact('booking', 'priests', 'isStaff'));
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

        // Send payment instructions email
        EmailService::sendPaymentInstructions($booking);

        // Create notification
        NotificationService::bookingAcknowledged($booking);

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

        // If approving, ensure selected priest is not on approved leave on the service date
        if ($request->verification_status === 'approved' && $booking->service_date) {
            $serviceDate = $booking->service_date instanceof \Carbon\Carbon ? $booking->service_date->toDateString() : \Carbon\Carbon::parse($booking->service_date)->toDateString();
            $onLeave = PriestLeave::where('priest_id', $request->priest_id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $serviceDate)
                ->whereDate('end_date', '>=', $serviceDate)
                ->exists();
            if ($onLeave) {
                return back()->with('error', 'Selected priest is on leave for the chosen date. Please choose another priest.');
            }
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

        // Send appropriate email based on verification status
        if ($request->verification_status === 'approved') {
            EmailService::sendBookingApproved($booking);
            NotificationService::bookingApproved($booking);
            NotificationService::paymentVerified($booking);
        } else {
            EmailService::sendBookingRejected($booking);
            NotificationService::bookingRejected($booking, $request->notes);
            NotificationService::paymentRejected($booking, $request->notes);
        }

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

        // Create notification
        NotificationService::bookingCompleted($booking);

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

        // Send rejection email
        EmailService::sendBookingRejected($booking);

        // Create notification
        NotificationService::bookingRejected($booking, $request->notes);

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

    public function uploadCertificate(Request $request, Booking $booking)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:8192',
        ]);

        if ($booking->certificate_path && Storage::disk('public')->exists($booking->certificate_path)) {
            Storage::disk('public')->delete($booking->certificate_path);
        }

        $file = $request->file('certificate');
        $path = $file->store('certificates', 'public');
        $booking->update(['certificate_path' => $path]);

        // Notify admin/staff and user
        NotificationService::certificateUploaded($booking, $file->getClientOriginalName());

        return back()->with('success', 'Certificate uploaded successfully.');
    }

    public function deleteCertificate(Booking $booking)
    {
        if ($booking->certificate_path && Storage::disk('public')->exists($booking->certificate_path)) {
            Storage::disk('public')->delete($booking->certificate_path);
        }
        $booking->update(['certificate_path' => null]);

        return back()->with('success', 'Certificate removed successfully.');
    }

    public function print(Booking $booking)
    {
        $booking->load(['user', 'service', 'payment']);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $html = view('admin.bookings.pdf', compact('booking'))->render();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('A4');
            return $pdf->stream('booking-' . $booking->id . '-receipt.pdf', ['Attachment' => false]);
        }

        abort(500, 'PDF engine not installed. Please install barryvdh/laravel-dompdf.');
    }

    public function certificate(Booking $booking)
    {
        $booking->load(['user', 'service', 'payment']);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            abort(500, 'PDF engine not installed. Please install barryvdh/laravel-dompdf.');
        }

        $serviceType = $booking->service->service_type ?? 'general';
        $view = 'admin.bookings.certificates.baptism';

        // In future, you can switch on $serviceType for other certificates
        $html = view($view, compact('booking'))->render();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('letter', 'portrait');
        return $pdf->stream('booking-' . $booking->id . '-certificate.pdf', ['Attachment' => false]);
    }
} 