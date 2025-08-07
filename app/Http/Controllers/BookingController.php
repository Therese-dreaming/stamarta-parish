<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function book(Service $service)
    {
        if (!$service->is_active) {
            return redirect()->route('services.index')->with('error', 'This service is not available for booking.');
        }

        return view('booking.step1', compact('service'));
    }

    public function step1(Request $request, Service $service)
    {
        // Get service-specific validation rules
        $validationRules = $this->getServiceValidationRules($service);
        
        $request->validate($validationRules);

        // Store step 1 data
        $request->session()->put('booking.step1', $request->all());
        $request->session()->put('booking.service_id', $service->id);

        return redirect()->route('booking.step2', $service);
    }

    public function step2(Request $request, Service $service)
    {
        if (!$request->session()->has('booking.step1')) {
            return redirect()->route('services.book', $service);
        }

        $step1Data = $request->session()->get('booking.step1');
        $selectedDate = $request->query('selected_date');

        // Get all bookings for this service to check availability (both pending and approved)
        $approvedBookings = Booking::where('service_id', $service->id)
            ->whereIn('status', ['pending', 'approved'])
            ->select('service_date', 'service_time', 'status')
            ->get();

        // Debug information
        \Log::info('Step2 Debug Info', [
            'service_id' => $service->id,
            'service_schedules' => $service->schedules,
            'approved_bookings_count' => $approvedBookings->count(),
            'approved_bookings' => $approvedBookings->toArray(),
            'selected_date' => $selectedDate
        ]);

        return view('booking.step2', compact('service', 'step1Data', 'approvedBookings', 'selectedDate'));
    }

    public function step2Store(Request $request, Service $service)
    {
        $request->validate([
            'selected_date' => 'required|date',
            'selected_time' => 'required|string',
        ]);

        // Check if the selected date/time is available
        $isAvailable = $this->isTimeSlotAvailable($service, $request->selected_date, $request->selected_time);
        
        if (!$isAvailable) {
            return back()->withErrors(['selected_time' => 'This time slot is no longer available. Please select another time.'])
                        ->with('error', 'Selected time slot is not available.');
        }

        // Store step 2 data
        $request->session()->put('booking.step2', $request->all());

        return redirect()->route('booking.step3', $service);
    }

    public function step3(Request $request, Service $service)
    {
        if (!$request->session()->has('booking.step1') || !$request->session()->has('booking.step2')) {
            return redirect()->route('services.book', $service);
        }

        $step1Data = $request->session()->get('booking.step1');
        $step2Data = $request->session()->get('booking.step2');

        return view('booking.step3', compact('service', 'step1Data', 'step2Data'));
    }

    public function step3Store(Request $request, Service $service)
    {
        // Build dynamic validation rules based on service type and conditional answers
        $validationRules = [
            'additional_notes' => 'nullable|string|max:500',
            'terms_accepted' => 'required|accepted',
        ];

        // Get service-specific requirements
        $serviceType = $service->service_type ?? 'general';
        $requiredDocuments = [];
        $conditionalDocuments = [];

        switch($serviceType) {
            case 'baptism':
                $requiredDocuments = ['birth_certificate'];
                $conditionalDocuments = [
                    'parents_marriage_contract' => 'parents_married',
                    'baptismal_permit' => 'from_other_parish'
                ];
                break;
            case 'wedding':
                $requiredDocuments = ['baptismal_certificate', 'confirmation_certificate', 'cenomar', 'marriage_license', 'id_pictures'];
                $conditionalDocuments = [
                    'civil_marriage_contract' => 'civilly_married',
                    'affidavit_cohabitation' => 'cohabiting'
                ];
                break;
            case 'blessing':
                $requiredDocuments = ['valid_id'];
                $conditionalDocuments = [
                    'proof_ownership' => 'has_ownership'
                ];
                break;
            default:
                $requiredDocuments = ['valid_id'];
                $conditionalDocuments = [
                    'additional_documents' => 'additional_docs'
                ];
        }

        // Add validation rules for required documents
        foreach ($requiredDocuments as $doc) {
            $validationRules["documents.{$doc}"] = 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120';
        }

        // Add validation rules for conditional documents based on answers
        $conditionalAnswers = $request->input('conditional_answers', []);
        foreach ($conditionalDocuments as $doc => $questionKey) {
            if (isset($conditionalAnswers[$questionKey]) && $conditionalAnswers[$questionKey] === 'yes') {
                $validationRules["documents.{$doc}"] = 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120';
            }
        }

        $request->validate($validationRules);

        // Get all booking data from session
        $step1Data = $request->session()->get('booking.step1');
        $step2Data = $request->session()->get('booking.step2');

        // Double-check slot availability before creating booking
        $isAvailable = $this->isTimeSlotAvailable($service, $step2Data['selected_date'], $step2Data['selected_time']);
        
        if (!$isAvailable) {
            return back()->withErrors(['selected_time' => 'This time slot is no longer available. Please select another time.']);
        }

        // Handle file uploads
        $uploadedDocuments = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $field => $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('documents/' . Auth::id(), $filename, 'public');
                    $uploadedDocuments[$field] = $path;
                }
            }
        }

        // Store conditional answers for reference
        $conditionalAnswers = $request->input('conditional_answers', []);
        $uploadedDocuments['conditional_answers'] = $conditionalAnswers;

        // Create the booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'service_date' => $step2Data['selected_date'],
            'service_time' => $step2Data['selected_time'],
            'contact_phone' => $step1Data['contact_phone'],
            'contact_address' => $step1Data['contact_address'],
            'additional_notes' => $step1Data['additional_notes'] ?? null,
            'requirements_submitted' => $uploadedDocuments,
            'additional_requirements' => $request->additional_notes ?? null,
            'custom_data' => $step1Data['custom_fields'] ?? [],
            'status' => 'pending',
        ]);

        // Clear session data
        $request->session()->forget(['booking.step1', 'booking.step2', 'booking.service_id']);

        return redirect()->route('booking.confirmation', $booking)
                        ->with('success', 'Your booking has been submitted successfully! We will contact you soon to confirm your appointment.');
    }

    public function confirmation(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('booking.confirmation', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = Auth::user()->bookings()->with('service')->latest()->paginate(10);
        
        return view('booking.my-bookings', compact('bookings'));
    }

    /**
     * Get service-specific validation rules
     */
    private function getServiceValidationRules(Service $service)
    {
        $baseRules = [
            'contact_phone' => 'required|string|max:20',
            'contact_address' => 'required|string|max:500',
            'additional_notes' => 'nullable|string|max:500',
        ];

        // Add custom fields based on service type
        $customFields = $service->custom_fields ?? [];
        foreach ($customFields as $field) {
            $rules = 'required|string|max:255';
            if (isset($field['validation'])) {
                $rules = $field['validation'];
            }
            $baseRules[$field['name']] = $rules;
        }

        return $baseRules;
    }

    /**
     * Check if a time slot is available
     */
    private function isTimeSlotAvailable(Service $service, $date, $time)
    {
        // Check if service is offered on this day
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        $schedules = $service->schedules ?? [];
        
        if (!isset($schedules[$dayOfWeek]) || empty($schedules[$dayOfWeek])) {
            return false;
        }

        // Check if the time is in the schedule
        if (!in_array($time, $schedules[$dayOfWeek])) {
            return false;
        }

        // Check for parochial activities that block this time slot
        $blockingActivities = \App\Models\ParochialActivity::active()
            ->onDate($date)
            ->get();

        foreach ($blockingActivities as $activity) {
            if ($activity->conflictsWithBooking($date, $time)) {
                return false;
            }
        }

        // Check if there are existing bookings for this date/time
        $existingBookings = Booking::where('service_id', $service->id)
            ->where('service_date', $date)
            ->where('service_time', $time)
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        // Check if we've reached the max slots
        if ($existingBookings >= $service->max_slots) {
            return false;
        }

        return true;
    }

    /**
     * Get available dates for a service (for calendar display)
     */
    public function getAvailableDates(Service $service)
    {
        $availableDates = [];
        $startDate = Carbon::now()->addDays($service->booking_restrictions['minimum_days'] ?? 1);
        $endDate = Carbon::now()->addDays($service->booking_restrictions['maximum_days'] ?? 90);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dayOfWeek = strtolower($date->format('l'));
            $schedules = $service->schedules ?? [];
            
            // Check if service is available on this day
            if (isset($schedules[$dayOfWeek]) && !empty($schedules[$dayOfWeek])) {
                $availableDates[] = $date->format('Y-m-d');
            }
        }

        return $availableDates;
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableTimeSlots(Service $service, $date)
    {
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        $schedules = $service->schedules ?? [];
        $allTimeSlots = $schedules[$dayOfWeek] ?? [];
        
        if (empty($allTimeSlots)) {
            return [];
        }

        // Check for parochial activities that block bookings on this date
        $blockingActivities = \App\Models\ParochialActivity::active()
            ->onDate($date)
            ->get();

        $availableSlots = [];
        
        foreach ($allTimeSlots as $timeSlot) {
            // Check if this time slot conflicts with any parochial activity
            $hasConflict = false;
            foreach ($blockingActivities as $activity) {
                if ($activity->conflictsWithBooking($date, $timeSlot)) {
                    $hasConflict = true;
                    break;
                }
            }

            if ($hasConflict) {
                continue; // Skip this time slot if it conflicts with an activity
            }

            $bookedCount = Booking::where('service_id', $service->id)
                ->where('service_date', $date)
                ->where('service_time', $timeSlot)
                ->whereIn('status', ['pending', 'approved'])
                ->count();

            $availableCount = $service->max_slots - $bookedCount;
            
            if ($availableCount > 0) {
                $availableSlots[] = [
                    'time' => $timeSlot,
                    'available_slots' => $availableCount,
                    'total_slots' => $service->max_slots,
                    'booked_slots' => $bookedCount
                ];
            }
        }

        return $availableSlots;
    }

    /**
     * Get time slots for a specific date via AJAX
     */
    public function getTimeSlots(Request $request, Service $service)
    {
        $date = $request->query('date');
        
        if (!$date) {
            return response()->json(['error' => 'Date parameter is required']);
        }

        try {
            $timeSlots = $this->getAvailableTimeSlots($service, $date);
            return response()->json(['timeSlots' => $timeSlots]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading time slots']);
        }
    }

    /**
     * Submit payment proof for a booking
     */
    public function showPayment(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Check if booking is in acknowledged status
        if ($booking->status !== 'acknowledged') {
            return redirect()->route('booking.my-bookings')->with('error', 'This booking is not ready for payment.');
        }

        $booking->load('service');
        
        return view('booking.payment', compact('booking'));
    }

    public function submitPayment(Request $request, Booking $booking)
    {
        // Ensure user can only submit payment for their own bookings
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('booking.my-bookings')->with('error', 'Unauthorized action.');
        }

        // Ensure booking is in acknowledged status
        if ($booking->status !== 'acknowledged') {
            return redirect()->route('booking.my-bookings')->with('error', 'Booking must be acknowledged first.');
        }

        $request->validate([
            'payment_method' => 'required|in:gcash,metrobank',
            'payment_reference' => 'required|string|max:255',
            'payment_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        try {
            // Handle payment proof upload
            $paymentProof = $request->file('payment_proof');
            if (!$paymentProof) {
                throw new \Exception('Payment proof file is required');
            }
            
            $filename = time() . '_payment_' . $booking->id . '.' . $paymentProof->getClientOriginalExtension();
            $path = $paymentProof->storeAs('payments/' . Auth::id(), $filename, 'public');
            
            if (!$path) {
                throw new \Exception('Failed to store payment proof file');
            }

            // Get the appropriate fee for this booking
            $feeInfo = $booking->service->getFeeForDate($booking->service_date);
            $totalFee = $feeInfo['amount'] ?? 0;

            // Create or update payment record
            $booking->payment()->updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'total_fee' => $totalFee,
                    'payment_method' => $request->payment_method,
                    'payment_reference' => $request->payment_reference,
                    'payment_proof' => $path,
                    'payment_notes' => $request->payment_notes,
                    'payment_status' => 'paid',
                    'payment_submitted_at' => now(),
                ]
            );

            // Update booking status to payment_hold
            $booking->update(['status' => 'payment_hold']);

            return redirect()->route('booking.my-bookings')->with('success', 'Payment proof submitted successfully! Your booking is now on payment hold and will be reviewed shortly.');
        } catch (\Exception $e) {
            \Log::error('Payment submission error: ' . $e->getMessage());
            \Log::error('Payment submission error trace: ' . $e->getTraceAsString());
            return redirect()->route('booking.payment', $booking)->with('error', 'Error submitting payment proof. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Booking $booking)
    {
        // Ensure user can only cancel their own bookings
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('booking.my-bookings')->with('error', 'Unauthorized action.');
        }

        // Ensure booking can be cancelled
        if (!in_array($booking->status, ['pending', 'acknowledged'])) {
            return redirect()->route('booking.my-bookings')->with('error', 'This booking cannot be cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('booking.my-bookings')->with('success', 'Booking cancelled successfully.');
    }
} 