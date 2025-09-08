<?php

require_once 'vendor/autoload.php';

// Start Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Service;

echo "=== TESTING FEE FORMATTING ===\n\n";

// Test 1: Service with no fees
echo "Test 1: Service with no fees\n";
$service = new Service();
$service->fees = null;
$feeInfo = $service->getFeeForDate('2025-10-01');
echo "Fee Info: " . json_encode($feeInfo) . "\n";
echo "Amount: " . ($feeInfo['amount'] ?? 'null') . "\n";
echo "Formatted: ₱" . number_format($feeInfo['amount'] ?? 0, 2) . "\n\n";

// Test 2: Service with simple fee structure
echo "Test 2: Service with simple fee structure\n";
$service2 = new Service();
$service2->fees = ['regular' => 100];
$feeInfo2 = $service2->getFeeForDate('2025-10-01');
echo "Fee Info: " . json_encode($feeInfo2) . "\n";
echo "Amount: " . ($feeInfo2['amount'] ?? 'null') . "\n";
echo "Formatted: ₱" . number_format($feeInfo2['amount'] ?? 0, 2) . "\n\n";

// Test 3: Service with complex fee structure
echo "Test 3: Service with complex fee structure\n";
$service3 = new Service();
$service3->fees = [
    'regular' => ['amount' => 150, 'description' => 'Regular Fee'],
    'urgent' => ['amount' => 200, 'description' => 'Urgent Fee', 'condition' => ['max_days' => 7]]
];
$feeInfo3 = $service3->getFeeForDate('2025-10-01');
echo "Fee Info: " . json_encode($feeInfo3) . "\n";
echo "Amount: " . ($feeInfo3['amount'] ?? 'null') . "\n";
echo "Formatted: ₱" . number_format($feeInfo3['amount'] ?? 0, 2) . "\n\n";

// Test 4: Test formatted_fees attribute
echo "Test 4: Formatted fees attribute\n";
$service4 = new Service();
$service4->fees = [
    'regular' => ['amount' => 100, 'description' => 'Regular Fee'],
    'urgent' => ['amount' => 150, 'description' => 'Urgent Fee']
];
echo "Formatted Fees: " . $service4->formatted_fees . "\n\n";

echo "=== ALL TESTS COMPLETED ===\n";
