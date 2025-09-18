<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\ParishSetting;

echo "Starting parish budget fix for existing verified bookings...\n";

// Get all verified booking payments
$verifiedBookings = Booking::with(['payment', 'service'])
    ->whereHas('payment', function($query) {
        $query->where('payment_status', 'verified');
    })
    ->get();

echo "Found " . $verifiedBookings->count() . " verified bookings.\n";

$totalAmount = 0;

// Calculate total amount from verified bookings
foreach ($verifiedBookings as $booking) {
    if ($booking->payment) {
        $totalAmount += $booking->payment->total_fee;
        echo "Booking #{$booking->id} - Service: " . ($booking->service->name ?? 'Unknown') . " - Amount: ₱" . number_format($booking->payment->total_fee, 2) . "\n";
    }
}

echo "\nTotal amount from verified bookings: ₱" . number_format($totalAmount, 2) . "\n";

// Check current parish total budget
$parishSetting = ParishSetting::where('key', 'parish_total_budget')->first();
$currentParishBudget = $parishSetting ? (float) $parishSetting->value : 0;

echo "Current parish total budget: ₱" . number_format($currentParishBudget, 2) . "\n";

if ($totalAmount > 0) {
    echo "\nUpdating parish total budget...\n";
    
    if ($parishSetting) {
        $newBudget = $currentParishBudget + $totalAmount;
        $parishSetting->update(['value' => $newBudget]);
        echo "Updated parish total budget from ₱" . number_format($currentParishBudget, 2) . " to ₱" . number_format($newBudget, 2) . "\n";
    } else {
        ParishSetting::create([
            'key' => 'parish_total_budget',
            'value' => $totalAmount,
            'description' => 'Total parish budget from all sources'
        ]);
        echo "Created parish_total_budget setting with value: ₱" . number_format($totalAmount, 2) . "\n";
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Total verified bookings processed: " . $verifiedBookings->count() . "\n";
    echo "Total amount added to parish budget: ₱" . number_format($totalAmount, 2) . "\n";
    echo "New parish total budget: ₱" . number_format($currentParishBudget + $totalAmount, 2) . "\n";
} else {
    echo "\nNo verified bookings found to process.\n";
}

echo "\nParish budget fix completed!\n";
