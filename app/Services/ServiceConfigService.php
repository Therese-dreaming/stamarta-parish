<?php

namespace App\Services;

class ServiceConfigService
{
    public static function getServiceConfigs()
    {
        return [
            'baptism' => [
                'name' => 'Baptism',
                'custom_fields' => [
                    'child_name' => [
                        'label' => "Child's Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Enter the child's full name"
                    ],
                    'child_birth_date' => [
                        'label' => "Child's Birth Date",
                        'type' => 'date',
                        'required' => true
                    ],
                    'parents_names' => [
                        'label' => "Parents' Names",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Father's Name and Mother's Name"
                    ],
                    'godparents' => [
                        'label' => 'Godparents',
                        'type' => 'textarea',
                        'required' => true,
                        'placeholder' => 'List the godparents names'
                    ]
                ],
                'requirements' => [
                    'Birth Certificate',
                    'Baptismal Certificate',
                    "Parents' IDs",
                    "Godparents' IDs"
                ]
            ],
            'wedding' => [
                'name' => 'Wedding',
                'custom_fields' => [
                    'groom_name' => [
                        'label' => "Groom's Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Enter the groom's full name"
                    ],
                    'bride_name' => [
                        'label' => "Bride's Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Enter the bride's full name"
                    ],
                    'groom_birth_date' => [
                        'label' => "Groom's Birth Date",
                        'type' => 'date',
                        'required' => true
                    ],
                    'bride_birth_date' => [
                        'label' => "Bride's Birth Date",
                        'type' => 'date',
                        'required' => true
                    ],
                    'wedding_date' => [
                        'label' => 'Preferred Wedding Date',
                        'type' => 'date',
                        'required' => true
                    ],
                    'witnesses' => [
                        'label' => 'Witnesses',
                        'type' => 'textarea',
                        'required' => true,
                        'placeholder' => 'List the witnesses names'
                    ]
                ],
                'requirements' => [
                    'Marriage License',
                    'Baptismal Certificates',
                    'Confirmation Certificates',
                    'Birth Certificates',
                    'Witnesses IDs',
                    'Pre-Cana Certificate'
                ]
            ],
            'blessing' => [
                'name' => 'Blessing',
                'custom_fields' => [
                    'person_name' => [
                        'label' => "Person's Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Enter the person's full name"
                    ],
                    'blessing_type' => [
                        'label' => 'Type of Blessing',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            'house' => 'House Blessing',
                            'vehicle' => 'Vehicle Blessing',
                            'business' => 'Business Blessing',
                            'other' => 'Other'
                        ]
                    ],
                    'blessing_details' => [
                        'label' => 'Blessing Details',
                        'type' => 'textarea',
                        'required' => false,
                        'placeholder' => 'Additional details about the blessing'
                    ]
                ],
                'requirements' => [
                    'Valid ID',
                    'Proof of Ownership (if applicable)',
                    'Special Requests (if any)'
                ]
            ],
            'funeral' => [
                'name' => 'Funeral Service',
                'custom_fields' => [
                    'deceased_name' => [
                        'label' => "Deceased Person's Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Enter the deceased person's full name"
                    ],
                    'date_of_death' => [
                        'label' => 'Date of Death',
                        'type' => 'date',
                        'required' => true
                    ],
                    'funeral_date' => [
                        'label' => 'Funeral Date',
                        'type' => 'date',
                        'required' => true
                    ],
                    'family_contact' => [
                        'label' => 'Family Contact Person',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Name of family contact person'
                    ]
                ],
                'requirements' => [
                    'Death Certificate',
                    'Burial Permit',
                    'Family Contact Information',
                    'Funeral Home Details'
                ]
            ]
        ];
    }

    public static function getServiceConfig($serviceType)
    {
        $configs = self::getServiceConfigs();
        return $configs[$serviceType] ?? null;
    }

    public static function getCustomFields($serviceType)
    {
        $config = self::getServiceConfig($serviceType);
        return $config['custom_fields'] ?? [];
    }

    public static function getRequirements($serviceType)
    {
        $config = self::getServiceConfig($serviceType);
        return $config['requirements'] ?? [];
    }

    public static function getServiceTypes()
    {
        return array_keys(self::getServiceConfigs());
    }
} 