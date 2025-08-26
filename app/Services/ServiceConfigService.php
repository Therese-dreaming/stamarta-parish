<?php

namespace App\Services;

class ServiceConfigService
{
    public static function getServiceConfigs()
    {
        return [
            'solo_baptism' => [
                'name' => 'Solo Baptism',
                'custom_fields' => [
                    // Child details
                    'child_last_name' => [
                        'label' => "Child's Last Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Last name"
                    ],
                    'child_first_name' => [
                        'label' => "Child's First Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "First name"
                    ],
                    'child_middle_initial' => [
                        'label' => "Child's Middle Initial",
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => "M.I."
                    ],
                    'child_birth_date' => [
                        'label' => "Child's Birth Date",
                        'type' => 'date',
                        'required' => true
                    ],
                    'place_of_birth' => [
                        'label' => 'Place of Birth',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'City/Municipality, Province'
                    ],
                    'nationality' => [
                        'label' => 'Nationality',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'e.g., Filipino'
                    ],
                    // Parents details
                    'father_last_name' => [
                        'label' => "Father's Last Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Last name"
                    ],
                    'father_first_name' => [
                        'label' => "Father's First Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "First name"
                    ],
                    'father_middle_initial' => [
                        'label' => "Father's Middle Initial",
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => "M.I."
                    ],
                    'mother_last_name' => [
                        'label' => "Mother's Last Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Last name"
                    ],
                    'mother_first_name' => [
                        'label' => "Mother's First Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "First name"
                    ],
                    'mother_middle_initial' => [
                        'label' => "Mother's Middle Initial",
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => "M.I."
                    ],
                    // Godparents as repeatable list
                    'godparents' => [
                        'label' => 'Godparents',
                        'type' => 'array',
                        'required' => true,
                        'placeholder' => 'Add godparent name(s) one at a time'
                    ],
                ],
                'requirements' => [
                    'Birth Certificate',
                    "Parents' IDs",
                    'Marriage Contract (if parents are married)',
                    'Baptismal Permit (if from another parish)'
                ]
            ],
            'group_baptism' => [
                'name' => 'Group Baptism',
                'custom_fields' => [
                    // Child details
                    'child_last_name' => [
                        'label' => "Child's Last Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Last name"
                    ],
                    'child_first_name' => [
                        'label' => "Child's First Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "First name"
                    ],
                    'child_middle_initial' => [
                        'label' => "Child's Middle Initial",
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => "M.I."
                    ],
                    'child_birth_date' => [
                        'label' => "Child's Birth Date",
                        'type' => 'date',
                        'required' => true
                    ],
                    'place_of_birth' => [
                        'label' => 'Place of Birth',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'City/Municipality, Province'
                    ],
                    'nationality' => [
                        'label' => 'Nationality',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'e.g., Filipino'
                    ],
                    // Parents details
                    'father_last_name' => [
                        'label' => "Father's Last Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Last name"
                    ],
                    'father_first_name' => [
                        'label' => "Father's First Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "First name"
                    ],
                    'father_middle_initial' => [
                        'label' => "Father's Middle Initial",
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => "M.I."
                    ],
                    'mother_last_name' => [
                        'label' => "Mother's Last Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Last name"
                    ],
                    'mother_first_name' => [
                        'label' => "Mother's First Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "First name"
                    ],
                    'mother_middle_initial' => [
                        'label' => "Mother's Middle Initial",
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => "M.I."
                    ],
                    // Godparents as repeatable list
                    'godparents' => [
                        'label' => 'Godparents',
                        'type' => 'array',
                        'required' => true,
                        'placeholder' => 'Add godparent name(s) one at a time'
                    ],
                ],
                'requirements' => [
                    'Birth Certificates',
                    "Parents' IDs",
                    'Marriage Contract (if parents are married)',
                    'Baptismal Permit (if from another parish)'
                ]
            ],
            'wedding' => [
                'name' => 'Wedding Service',
                'custom_fields' => [
                    'groom_name' => [
                        'label' => "Groom's Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Enter the groom's full name"
                    ],
                    'groom_religion' => [
                        'label' => "Groom's Religion",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'e.g., Roman Catholic'
                    ],
                    'bride_name' => [
                        'label' => "Bride's Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => "Enter the bride's full name"
                    ],
                    'bride_religion' => [
                        'label' => "Bride's Religion",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'e.g., Roman Catholic'
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
                    'witnesses' => [
                        'label' => 'Witnesses',
                        'type' => 'array',
                        'required' => true,
                        'placeholder' => 'Add witness name(s) one at a time'
                    ]
                ],
                'requirements' => [
                    'Marriage License',
                    'Baptismal Certificates',
                    'Confirmation Certificates',
                    'Birth Certificates',
                    'Witnesses IDs',
                    'Pre-Cana Certificate',
                    'Civil Marriage Contract (if already civilly married)',
                    'Affidavit of Cohabitation (if currently cohabiting)'
                ]
            ],
            'blessing' => [
                'name' => 'Blessing',
                'custom_fields' => [
                    'person_last_name' => [
                        'label' => "Person's Last Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Last name'
                    ],
                    'person_first_name' => [
                        'label' => "Person's First Name",
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'First name'
                    ],
                    'person_middle_initial' => [
                        'label' => "Person's Middle Initial",
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => 'M.I.'
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