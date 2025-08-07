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
                'service_type' => 'baptism',
                'requirements' => [
                    'Birth Certificate',
                    'Baptismal Certificate',
                    "Parents' IDs",
                    "Godparents' IDs"
                ],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Regular Fee',
                        'amount' => 1500.00,
                        'condition' => [
                            'min_days' => 10,
                            'max_days' => null
                        ]
                    ],
                    [
                        'type' => 'rush',
                        'description' => 'Rush Fee (under 10 days)',
                        'amount' => 2500.00,
                        'condition' => [
                            'min_days' => 1,
                            'max_days' => 9
                        ]
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
                'service_type' => 'baptism',
                'requirements' => [
                    'Birth Certificates',
                    'Baptismal Certificates',
                    "Parents' IDs",
                    "Godparents' IDs"
                ],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Regular Fee (per child)',
                        'amount' => 1200.00,
                        'condition' => [
                            'min_days' => 10,
                            'max_days' => null
                        ]
                    ],
                    [
                        'type' => 'rush',
                        'description' => 'Rush Fee (under 10 days)',
                        'amount' => 2000.00,
                        'condition' => [
                            'min_days' => 1,
                            'max_days' => 9
                        ]
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
                'name' => 'Wedding',
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
                    'Witnesses IDs',
                    'Pre-Cana Certificate'
                ],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Regular Fee',
                        'amount' => 5000.00,
                        'condition' => [
                            'min_days' => 90,
                            'max_days' => null
                        ]
                    ],
                    [
                        'type' => 'rush',
                        'description' => 'Rush Fee (under 3 months)',
                        'amount' => 7500.00,
                        'condition' => [
                            'min_days' => 30,
                            'max_days' => 89
                        ]
                    ]
                ],
                'schedules' => [
                    'friday' => ['2:00 PM', '5:00 PM'],
                    'saturday' => ['10:00 AM', '2:00 PM', '5:00 PM']
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
                    'Valid ID',
                    'Proof of Ownership (if applicable)',
                    'Special Requests (if any)'
                ],
                'fees' => [
                    [
                        'type' => 'regular',
                        'description' => 'Regular Fee',
                        'amount' => 800.00,
                        'condition' => [
                            'min_days' => 7,
                            'max_days' => null
                        ]
                    ],
                    [
                        'type' => 'rush',
                        'description' => 'Rush Fee (under 7 days)',
                        'amount' => 1200.00,
                        'condition' => [
                            'min_days' => 1,
                            'max_days' => 6
                        ]
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