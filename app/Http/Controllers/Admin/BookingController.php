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
        $bookings = Booking::with(['user', 'service', 'priest'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'pending' => Booking::where('status', 'pending')->count(),
            'acknowledged' => Booking::where('status', 'acknowledged')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'service', 'priest']);
        $priests = Priest::where('is_active', true)->get();
        
        return view('admin.bookings.show', compact('booking', 'priests'));
    }

    public function acknowledge(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be acknowledged.');
        }

        $booking->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => auth()->id(),
        ]);

        return back()->with('success', 'Booking acknowledged and payment put on hold successfully.');
    }

    public function verifyPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'verification_status' => 'required|in:approved,rejected',
            'priest_id' => 'required_if:verification_status,approved|exists:priests,id',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($booking->status !== 'acknowledged') {
            return back()->with('error', 'Only acknowledged bookings can be verified.');
        }

        $status = $request->verification_status === 'approved' ? 'approved' : 'rejected';
        
        $updateData = [
            'status' => $status,
            'payment_status' => $request->verification_status === 'approved' ? 'verified' : 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'notes' => $request->notes,
        ];

        if ($request->verification_status === 'approved') {
            $updateData['priest_id'] = $request->priest_id;
            $updateData['approved_at'] = now();
            $updateData['approved_by'] = auth()->id();
        }

        $booking->update($updateData);

        $message = $request->verification_status === 'approved' 
            ? 'Payment verified and booking approved successfully.' 
            : 'Payment rejected successfully.';

        return back()->with('success', $message);
    }

    public function complete(Booking $booking)
    {
        if ($booking->status !== 'approved') {
            return back()->with('error', 'Only approved bookings can be marked as completed.');
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Booking marked as completed successfully.');
    }

    public function reject(Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'acknowledged'])) {
            return back()->with('error', 'This booking cannot be rejected.');
        }

        $booking->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
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
        if (!$booking->payment_proof) {
            return back()->with('error', 'Payment proof not found.');
        }

        if (!Storage::disk('public')->exists($booking->payment_proof)) {
            return back()->with('error', 'Payment proof file not found.');
        }

        return Storage::disk('public')->download($booking->payment_proof);
    }

    public function updateNotes(Request $request, Booking $booking)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Notes updated successfully.');
    }
} 