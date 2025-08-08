<?php

namespace App\Http\Controllers\Priest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingAction;
use App\Models\ParochialActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        $query = Booking::where('priest_id', $priestRecord->id)
            ->with(['user', 'service']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('service_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('service_date', '<=', $request->date_to);
        }

        // Search by user name or booking ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics for assigned bookings
        $stats = [
            'total' => Booking::where('priest_id', $priestRecord->id)->count(),
            'pending' => Booking::where('priest_id', $priestRecord->id)->where('status', 'pending')->count(),
            'acknowledged' => Booking::where('priest_id', $priestRecord->id)->where('status', 'acknowledged')->count(),
            'payment_hold' => Booking::where('priest_id', $priestRecord->id)->where('status', 'payment_hold')->count(),
            'approved' => Booking::where('priest_id', $priestRecord->id)->where('status', 'approved')->count(),
            'completed' => Booking::where('priest_id', $priestRecord->id)->where('status', 'completed')->count(),
            'rejected' => Booking::where('priest_id', $priestRecord->id)->where('status', 'rejected')->count(),
        ];

        return view('priest.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Ensure the booking is assigned to this priest
        if ($booking->priest_id !== $priestRecord->id) {
            abort(403, 'You can only view bookings assigned to you.');
        }

        $booking->load(['user', 'service', 'payment', 'actions.performedBy']);

        return view('priest.bookings.show', compact('booking'));
    }

    public function calendar()
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Get assigned bookings for calendar
        $bookings = Booking::where('priest_id', $priestRecord->id)
            ->with(['user', 'service'])
            ->get();

        // Get parochial activities that might conflict
        $activities = ParochialActivity::where('status', 'active')
            ->where('event_date', '>=', Carbon::now()->subDays(30))
            ->where('event_date', '<=', Carbon::now()->addDays(90))
            ->get();

        return view('priest.bookings.calendar', compact('bookings', 'activities'));
    }

    public function acknowledge(Booking $booking)
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Ensure the booking is assigned to this priest
        if ($booking->priest_id !== $priestRecord->id) {
            abort(403, 'You can only acknowledge bookings assigned to you.');
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be acknowledged.');
        }

        $booking->update(['status' => 'acknowledged']);

        // Record the action
        BookingAction::create([
            'booking_id' => $booking->id,
            'action' => 'acknowledged',
            'performed_by' => $priest->id,
            'notes' => 'Booking acknowledged by assigned priest'
        ]);

        return back()->with('success', 'Booking has been acknowledged successfully.');
    }

    public function complete(Booking $booking)
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Ensure the booking is assigned to this priest
        if ($booking->priest_id !== $priestRecord->id) {
            abort(403, 'You can only complete bookings assigned to you.');
        }

        if ($booking->status !== 'approved') {
            return back()->with('error', 'Only approved bookings can be completed.');
        }

        $booking->update(['status' => 'completed']);

        // Record the action
        BookingAction::create([
            'booking_id' => $booking->id,
            'action' => 'completed',
            'performed_by' => $priest->id,
            'notes' => 'Service completed by assigned priest'
        ]);

        return back()->with('success', 'Booking has been marked as completed.');
    }

    public function reject(Request $request, Booking $booking)
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Ensure the booking is assigned to this priest
        if ($booking->priest_id !== $priestRecord->id) {
            abort(403, 'You can only reject bookings assigned to you.');
        }

        if (!in_array($booking->status, ['pending', 'acknowledged', 'payment_hold'])) {
            return back()->with('error', 'This booking cannot be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        // Record the action
        BookingAction::create([
            'booking_id' => $booking->id,
            'action' => 'rejected',
            'performed_by' => $priest->id,
            'notes' => $request->rejection_reason
        ]);

        return back()->with('success', 'Booking has been rejected.');
    }

    public function verifyPayment(Booking $booking)
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Ensure the booking is assigned to this priest
        if ($booking->priest_id !== $priestRecord->id) {
            abort(403, 'You can only verify payments for bookings assigned to you.');
        }

        if ($booking->status !== 'payment_hold') {
            return back()->with('error', 'Only bookings on payment hold can have payments verified.');
        }

        if (!$booking->payment || !$booking->payment->payment_proof) {
            return back()->with('error', 'No payment proof found for this booking.');
        }

        $booking->update(['status' => 'approved']);

        // Record the action
        BookingAction::create([
            'booking_id' => $booking->id,
            'action' => 'payment_verified',
            'performed_by' => $priest->id,
            'notes' => 'Payment verified by assigned priest'
        ]);

        return back()->with('success', 'Payment has been verified and booking is now approved.');
    }

    public function downloadDocument(Booking $booking, $documentType)
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Ensure the booking is assigned to this priest
        if ($booking->priest_id !== $priestRecord->id) {
            abort(403, 'You can only download documents for bookings assigned to you.');
        }

        $customData = $booking->custom_data ?? [];
        
        if (!isset($customData[$documentType])) {
            abort(404, 'Document not found.');
        }

        $filePath = storage_path('app/' . $customData[$documentType]);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath);
    }

    public function downloadPaymentProof(Booking $booking)
    {
        $priest = auth()->user();
        
        // Get the priest record associated with this user
        $priestRecord = $priest->priest;
        
        if (!$priestRecord) {
            abort(403, 'No priest record found for this user.');
        }
        
        // Ensure the booking is assigned to this priest
        if ($booking->priest_id !== $priestRecord->id) {
            abort(403, 'You can only download payment proof for bookings assigned to you.');
        }

        if (!$booking->payment || !$booking->payment->payment_proof) {
            abort(404, 'Payment proof not found.');
        }

        $filePath = storage_path('app/' . $booking->payment->payment_proof);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath);
    }
} 