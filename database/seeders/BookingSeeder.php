<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\Priest;
use App\Models\BookingAction;
use App\Models\BookingPayment;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing services, users, and priests
        $services = Service::all();
        $users = User::where('role', 'user')->get();
        $priests = Priest::all();
        
        if ($services->isEmpty() || $users->isEmpty() || $priests->isEmpty()) {
            $this->command->info('Skipping BookingSeeder: Missing required data (services, users, or priests)');
            return;
        }

        $sampleBookings = [
            [
                'service_name' => 'Solo Baptism',
                'status' => 'pending',
                'contact_phone' => '+63 912 345 6789',
                'contact_address' => '123 Main Street, Hagonoy, Bulacan',
                'additional_notes' => 'Please schedule in the morning if possible',
                'requirements_submitted' => [
                    'birth_certificate' => 'documents/1/1754589908_birth_certificate.docx',
                    'parents_ids' => 'documents/1/1754523930_birth_certificate.pdf',
                    'conditional_answers' => [
                        'parents_married' => 'yes',
                        'from_another_parish' => 'no'
                    ],
                    'marriage_contract' => 'documents/1/1754589908_marriage_contract.pdf'
                ],
                'custom_data' => [
                    'child_name' => 'Maria Santos Cruz',
                    'child_birth_date' => '2023-06-15',
                    'parents_names' => 'Juan Cruz and Ana Santos',
                    'godparents' => 'Pedro Santos and Carmen Reyes'
                ],
                'service_date' => '2025-01-20',
                'service_time' => '10:00 AM',
                'create_payment' => false,
                'create_actions' => false
            ],
            [
                'service_name' => 'Group Baptism',
                'status' => 'approved',
                'contact_phone' => '+63 923 456 7890',
                'contact_address' => '456 Church Road, Hagonoy, Bulacan',
                'additional_notes' => 'Group of 3 children from the same family',
                'requirements_submitted' => [
                    'birth_certificates' => 'documents/1/1754589908_birth_certificates.pdf',
                    'parents_ids' => 'documents/1/1754523930_parents_ids.pdf',
                    'conditional_answers' => [
                        'parents_married' => 'yes',
                        'from_another_parish' => 'no'
                    ],
                    'marriage_contract' => 'documents/1/1754589908_marriage_contract.pdf'
                ],
                'custom_data' => [
                    'children_count' => 3,
                    'children_details' => 'Pedro Santos (2022-08-10), Ana Santos (2023-01-25), Miguel Santos (2023-11-30)'
                ],
                'service_date' => '2025-01-25',
                'service_time' => '2:00 PM',
                'create_payment' => true,
                'payment_data' => [
                    'total_fee' => 3600.00,
                    'payment_method' => 'gcash',
                    'payment_status' => 'pending',
                    'payment_reference' => 'GCASH-2025-001'
                ],
                'create_actions' => true,
                'actions' => ['acknowledged', 'approved']
            ],
            [
                'service_name' => 'Wedding Service',
                'status' => 'pending',
                'contact_phone' => '+63 934 567 8901',
                'contact_address' => '789 Wedding Lane, Hagonoy, Bulacan',
                'additional_notes' => 'Traditional Filipino wedding ceremony',
                'requirements_submitted' => [
                    'marriage_license' => 'documents/1/1754589908_marriage_license.pdf',
                    'baptismal_certificates' => 'documents/1/1754523930_baptismal_certificates.pdf',
                    'confirmation_certificates' => 'documents/1/1754589908_confirmation_certificates.pdf',
                    'birth_certificates' => 'documents/1/1754523930_birth_certificates.pdf',
                    'witnesses_ids' => 'documents/1/1754589908_witnesses_ids.pdf',
                    'pre_cana_certificate' => 'documents/1/1754523930_pre_cana_certificate.pdf',
                    'conditional_answers' => [
                        'already_civilly_married' => 'no',
                        'currently_cohabiting' => 'no'
                    ]
                ],
                'custom_data' => [
                    'groom_name' => 'Carlos Mendoza',
                    'groom_birth_date' => '1995-03-20',
                    'bride_name' => 'Isabella Garcia',
                    'bride_birth_date' => '1997-07-15',
                    'wedding_date' => '2025-06-15',
                    'witnesses' => 'Miguel Mendoza and Sofia Garcia'
                ],
                'service_date' => '2025-06-15',
                'service_time' => '2:00 PM',
                'create_payment' => false,
                'create_actions' => false
            ],
            [
                'service_name' => 'Blessing',
                'status' => 'completed',
                'contact_phone' => '+63 945 678 9012',
                'contact_address' => '321 Blessing Street, Hagonoy, Bulacan',
                'additional_notes' => 'House blessing for new home',
                'requirements_submitted' => [
                    'valid_id' => 'documents/1/1754523930_valid_id.pdf',
                    'conditional_answers' => [
                        'proof_of_ownership' => 'yes',
                        'special_requests' => 'yes'
                    ],
                    'proof_of_ownership' => 'documents/1/1754589908_proof_of_ownership.pdf',
                    'special_requests' => 'documents/1/1754523930_special_requests.pdf'
                ],
                'custom_data' => [
                    'person_name' => 'Roberto Santos',
                    'blessing_type' => 'house',
                    'blessing_details' => 'House blessing for new home with prayer for family prosperity and protection'
                ],
                'service_date' => '2025-01-10',
                'service_time' => '10:00 AM',
                'create_payment' => true,
                'payment_data' => [
                    'total_fee' => 800.00,
                    'payment_method' => 'metrobank',
                    'payment_status' => 'verified',
                    'payment_reference' => 'MB-2025-001',
                    'payment_proof' => 'payments/1/1754596145_payment_1.jpg'
                ],
                'create_actions' => true,
                'actions' => ['acknowledged', 'approved', 'completed']
            ],
            [
                'service_name' => 'Solo Baptism',
                'status' => 'acknowledged',
                'contact_phone' => '+63 956 789 0123',
                'contact_address' => '654 Baptism Avenue, Hagonoy, Bulacan',
                'additional_notes' => 'First child baptism',
                'requirements_submitted' => [
                    'birth_certificate' => 'documents/1/1754589908_birth_certificate.pdf',
                    'parents_ids' => 'documents/1/1754523930_parents_ids.pdf',
                    'conditional_answers' => [
                        'parents_married' => 'no',
                        'from_another_parish' => 'yes'
                    ],
                    'baptismal_permit' => 'documents/1/1754589908_baptismal_permit.pdf'
                ],
                'custom_data' => [
                    'child_name' => 'Gabriel Reyes',
                    'child_birth_date' => '2023-09-20',
                    'parents_names' => 'Antonio Reyes and Maria Flores',
                    'godparents' => 'Jose Reyes and Ana Flores'
                ],
                'service_date' => '2025-02-01',
                'service_time' => '2:00 PM',
                'create_payment' => false,
                'create_actions' => true,
                'actions' => ['acknowledged']
            ]
        ];

        foreach ($sampleBookings as $bookingData) {
            // Find the service by name
            $service = $services->where('name', $bookingData['service_name'])->first();
            if (!$service) continue;

            // Get a random user and priest
            $user = $users->random();
            $priest = $priests->random();

            // Create the booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'priest_id' => $priest->id,
                'service_date' => $bookingData['service_date'],
                'service_time' => $bookingData['service_time'],
                'contact_phone' => $bookingData['contact_phone'],
                'contact_address' => $bookingData['contact_address'],
                'additional_notes' => $bookingData['additional_notes'],
                'requirements_submitted' => $bookingData['requirements_submitted'],
                'custom_data' => $bookingData['custom_data'],
                'status' => $bookingData['status'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 29))
            ]);

            // Create payment record if needed
            if ($bookingData['create_payment'] && isset($bookingData['payment_data'])) {
                $this->createPaymentRecord($booking, $bookingData['payment_data']);
            }

            // Create action records if needed
            if ($bookingData['create_actions'] && isset($bookingData['actions'])) {
                $this->createActionRecords($booking, $bookingData['actions']);
            }
        }

        $this->command->info('Sample bookings created successfully!');
    }

    private function createPaymentRecord($booking, $paymentData): void
    {
        $now = now();
        
        BookingPayment::create([
            'booking_id' => $booking->id,
            'total_fee' => $paymentData['total_fee'],
            'payment_method' => $paymentData['payment_method'],
            'payment_status' => $paymentData['payment_status'],
            'payment_reference' => $paymentData['payment_reference'],
            'payment_proof' => $paymentData['payment_proof'] ?? null,
            'payment_notes' => 'Sample payment data for testing',
            'payment_submitted_at' => $now->subDays(rand(1, 5)),
            'payment_verified_at' => $paymentData['payment_status'] === 'verified' ? $now->subDays(rand(1, 3)) : null,
            'verified_by' => $paymentData['payment_status'] === 'verified' ? 1 : null, // Assuming admin user ID 1
            'created_at' => $now->subDays(rand(1, 5)),
            'updated_at' => $now->subDays(rand(1, 5))
        ]);
    }

    private function createActionRecords($booking, $actions): void
    {
        $now = now();
        $actionDates = [];
        
        // Generate realistic action dates
        foreach ($actions as $index => $action) {
            $daysAgo = count($actions) - $index + rand(1, 3);
            $actionDates[$action] = $now->subDays($daysAgo);
        }

        foreach ($actions as $actionType) {
            $actionDate = $actionDates[$actionType];
            
            BookingAction::create([
                'booking_id' => $booking->id,
                'action_type' => $actionType,
                'notes' => $this->getActionNotes($actionType),
                'performed_by' => 1, // Assuming admin user ID 1
                'priest_id' => $booking->priest_id,
                'created_at' => $actionDate,
                'updated_at' => $actionDate
            ]);
        }
    }

    private function getActionNotes($actionType): string
    {
        return match($actionType) {
            'acknowledged' => 'Booking acknowledged by parish staff',
            'approved' => 'Booking approved by parish staff',
            'rejected' => 'Booking rejected by parish staff',
            'completed' => 'Service completed successfully',
            default => 'Action performed by parish staff'
        };
    }
} 