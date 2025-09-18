<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Solo Baptism',
                'description' => 'Individual baptism ceremony for infants and children.',
                'duration_minutes' => 60,
                'max_slots' => 1,
                'is_active' => true,
                'service_type' => 'solo_baptism',
                'requirements' => [
                    'Birth Certificate',
                    "Parents' IDs",
                    'Marriage Contract (if parents are married)'
                ],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Regular Fee',
                        'amount' => 1500.00
                    ]
                ],
                'schedules' => [
                    'monday' => ['10:00 AM', '2:00 PM', '5:00 PM'],
                    'tuesday' => ['10:00 AM'],
                    'saturday' => ['2:00 PM']
                ],
                'booking_restrictions' => [
                    'minimum_days' => 1,
                    'maximum_days' => 90
                ],
                'notes' => 'Please bring all required documents and arrive 30 minutes before the scheduled time.'
            ],
            [
                'name' => 'Group Baptism',
                'description' => 'Group baptism ceremony for multiple children.',
                'duration_minutes' => 120,
                'max_slots' => 5,
                'is_active' => true,
                'service_type' => 'group_baptism',
                'requirements' => [
                    'Birth Certificates',
                    "Parents' IDs",
                    'Marriage Contract (if parents are married)'
                ],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Regular Fee (per child)',
                        'amount' => 1200.00
                    ]
                ],
                'schedules' => [
                    'saturday' => ['9:00 AM', '2:00 PM']
                ],
                'booking_restrictions' => [
                    'minimum_days' => 1,
                    'maximum_days' => 90
                ],
                'notes' => 'Group baptisms are scheduled on Saturdays only. Maximum 5 children per group.'
            ],
            [
                'name' => 'Wedding Service',
                'description' => 'Sacramental wedding ceremony.',
                'duration_minutes' => 90,
                'max_slots' => 1,
                'is_active' => true,
                'service_type' => 'wedding',
                'requirements' => [
                    'Marriage License',
                    'Baptismal Certificates',
                    'Confirmation Certificates',
                    'Birth Certificates',
                    'Pre-Cana Certificate',
                    'Civil Marriage Contract (if already civilly married)'
                ],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Regular Fee',
                        'amount' => 5000.00
                    ]
                ],
                'schedules' => [
                    'saturday' => ['10:00 AM', '2:00 PM'],
                    'sunday' => ['2:00 PM']
                ],
                'booking_restrictions' => [
                    'minimum_days' => 1,
                    'maximum_days' => 365
                ],
                'notes' => 'Weddings require at least 30 days advance booking. Pre-Cana seminar is mandatory.'
            ],
            [
                'name' => 'Blessing',
                'description' => 'Various blessing services for homes, vehicles, businesses, etc.',
                'duration_minutes' => 45,
                'max_slots' => 1,
                'is_active' => true,
                'service_type' => 'blessing',
                'requirements' => [
                    'Valid ID'
                ],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Regular Fee',
                        'amount' => 800.00
                    ]
                ],
                'schedules' => [
                    'monday' => ['10:00 AM', '2:00 PM'],
                    'wednesday' => ['10:00 AM', '2:00 PM'],
                    'friday' => ['10:00 AM', '2:00 PM']
                ],
                'booking_restrictions' => [
                    'minimum_days' => 1,
                    'maximum_days' => 60
                ],
                'notes' => 'Blessing services are available for various purposes. Please specify the type of blessing needed.'
            ]
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }
    }
} 