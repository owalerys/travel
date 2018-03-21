<?php

return [

    'schemas' => [

        '1' => [
            'version' => '1',
            'categories' => [
                'airline_contact' => [
                    'title' => 'Airline Contact Information',
                    'slug' => 'airline_contact',
                    'association' => 'airline',
                    'type' => ['content', 'link'],
                    'accept_new_submissions' => true,
                    'fields' => [
                        'email_agency' => [
                            'title' => 'Emails',
                            'slug' => 'email_agency',
                            'filter' => 'email',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'agency'
                        ],
                        'phone_agency' => [
                            'title' => 'Phones',
                            'slug' => 'phone_agency',
                            'filter' => 'phone',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'agency'
                        ],
                        'site_agency' => [
                            'title' => 'Sites',
                            'slug' => 'site_agency',
                            'filter' => 'url',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'agency'
                        ],
                        'email_customer' => [
                            'title' => 'Emails',
                            'slug' => 'email_customer',
                            'filter' => 'email',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'customer'
                        ],
                        'phone_customer' => [
                            'title' => 'Phones',
                            'slug' => 'phone_customer',
                            'filter' => 'phone',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'customer'
                        ],
                        'site_customer' => [
                            'title' => 'Sites',
                            'slug' => 'site_customer',
                            'filter' => 'url',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'customer'
                        ]
                    ],
                    'field_categories' => [
                        'agency' => [
                            'title' => 'Agency Contacts',
                            'slug' => 'agency',
                            'at_least_one' => false
                        ],
                        'customer' => [
                            'title' => 'Customer Contacts',
                            'slug' => 'customer',
                            'at_least_one' => false
                        ]
                    ]
                ],
                'schedule_changes' => [
                    'title' => 'Schedule Changes',
                    'slug' => 'schedule_changes',
                    'association' => 'airline',
                    'type' => 'content',
                    'accept_new_submissions' => true,
                    'fields' => [
                        'exchange_class_of_service' => [
                            'title' => 'Class of Service',
                            'slug' => 'exchange_class_of_service',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_waiver' => [
                            'title' => 'Waiver',
                            'slug' => 'exchange_waiver',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_endorsement_line' => [
                            'title' => 'Endorsement Line',
                            'slug' => 'exchange_endorsement_line',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_osi' => [
                            'title' => 'OSI Required',
                            'slug' => 'exchange_osi',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_tour_code' => [
                            'title' => 'Tour Code',
                            'slug' => 'exchange_tour_code',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_misc' => [
                            'title' => 'Additional Info',
                            'slug' => 'exchange_misc',
                            'filter' => 'paragraph',
                            'multiple' => true,
                            'required' => false,
                            'additional_info' => true,
                            'custom_sub_heading' => true,
                            'category' => 'exchange'
                        ],
                        'refund_class_of_service' => [
                            'title' => 'Class of Service',
                            'slug' => 'refund_class_of_service',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'refund_eligibility' => [
                            'title' => 'Eligibility',
                            'slug' => 'refund_eligibility',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'refund_waiver' => [
                            'title' => 'Waiver',
                            'slug' => 'refund_waiver',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'refund_endorsement_line' => [
                            'title' => 'Endorsement Line',
                            'slug' => 'refund_endorsement_line',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'refund_osi' => [
                            'title' => 'OSI Required',
                            'slug' => 'refund_osi',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'refund_tour_code' => [
                            'title' => 'Tour Code',
                            'slug' => 'refund_tour_code',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'refund_misc' => [
                            'title' => 'Additional Info',
                            'slug' => 'refund_misc',
                            'filter' => 'paragraph',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'refund'
                        ]
                    ],
                    'field_categories' => [
                        'exchange' => [
                            'title' => 'Exchanges',
                            'slug' => 'exchange',
                            'at_least_one' => true
                        ],
                        'refund' => [
                            'title' => 'Refunds',
                            'slug' => 'refund',
                            'at_least_one' => true
                        ]
                    ]
                ],
                'name_correction' => [
                    'title' => 'Name Correction',
                    'slug' => 'name_correction',
                    'association' => 'airline',
                    'type' => 'content',
                    'accept_new_submissions' => true,
                    'fields' => [
                        'maximum_letters' => [
                            'title' => 'Maximum # of Letters',
                            'slug' => 'maximum_letters',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'waiver' => [
                            'title' => 'Waiver',
                            'slug' => 'waiver',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'osi' => [
                            'title' => 'OSI',
                            'slug' => 'osi',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'endorsement_line' => [
                            'title' => 'Endorsement Line',
                            'slug' => 'endorsement_line',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'existing_pnr' => [
                            'title' => 'Modify Existing PNR',
                            'slug' => 'existing_pnr',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'new_pnr' => [
                            'title' => 'New PNR Required',
                            'slug' => 'new_pnr',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'reissue_by_agency' => [
                            'title' => 'Reissue by Agency',
                            'slug' => 'reissue_by_agency',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'adm_fee' => [
                            'title' => 'ADM Fee',
                            'slug' => 'adm_fee',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'additional_info' => [
                            'title' => 'Additional Info',
                            'slug' => 'additional_info',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true,
                            'multiple' => true
                        ],
                    ],
                    'field_categories' => []
                ],
                'death_medical' => [
                    'title' => 'Death/Medical Policy',
                    'slug' => 'death_medical',
                    'association' => 'airline',
                    'type' => 'content',
                    'accept_new_submissions' => true,
                    'fields' => [
                        'relation_to_traveler_death' => [
                            'title' => 'Relation to Traveler',
                            'slug' => 'relation_to_traveler_death',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'death'
                        ],
                        'required_documentation_death' => [
                            'title' => 'Required Documentation',
                            'slug' => 'required_documentation_death',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'death'
                        ],
                        'steps_death' => [
                            'title' => 'Steps to Follow',
                            'slug' => 'steps_death',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'death',
                            'multiple' => true,
                            'custom_sub_heading' => true
                        ],
                        'provisions_medical' => [
                            'title' => 'Provisions',
                            'slug' => 'provisions_medical',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'medical'
                        ],
                        'relation_to_traveler_medical' => [
                            'title' => 'Relation to Traveler',
                            'slug' => 'relation_to_traveler_medical',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'medical'
                        ],
                        'required_documentation_medical' => [
                            'title' => 'Required Documentation',
                            'slug' => 'required_documentation_medical',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'medical'
                        ],
                        'steps_medical' => [
                            'title' => 'Steps to Follow',
                            'slug' => 'steps_medical',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'medical',
                            'multiple' => true,
                            'custom_sub_heading' => true
                        ],
                    ],
                    'field_categories' => [
                        'death' => [
                            'title' => 'Death',
                            'slug' => 'death',
                            'at_least_one' => false
                        ],
                        'medical' => [
                            'title' => 'Medical',
                            'slug' => 'medical',
                            'at_least_one' => false
                        ]
                    ]
                ],
                'baggage_allowance' => [
                    'title' => 'Baggage Allowance',
                    'slug' => 'baggage_allowance',
                    'association' => 'airline',
                    'type' => 'link',
                    'accept_new_submissions' => true,
                    'fields' => [],
                    'field_categories' => []
                ],
                'travel_advisory_registration' => [
                    'title' => 'Travel Advisory Distribution List',
                    'slug' => 'travel_advisory_registration',
                    'association' => 'airline',
                    'type' => 'content',
                    'accept_new_submissions' => true,
                    'fields' => [
                        'registration_url' => [
                            'title' => 'Link',
                            'slug' => 'registration_url',
                            'filter' => 'url',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'registration_where' => [
                            'title' => 'Where to Register',
                            'slug' => 'registration_where',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'registration_steps' => [
                            'title' => 'Steps to Register',
                            'slug' => 'registration_steps',
                            'filter' => 'paragraph',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true
                        ]
                    ],
                    'field_categories' => []
                ],
                'travel_advisories' => [
                    'title' => 'Travel Advisories - Active',
                    'slug' => 'travel_advisories',
                    'association' => 'airline',
                    'type' => 'content',
                    'accept_new_submissions' => true,
                    'fields' => [
                        'advisory_url' => [
                            'title' => 'Link',
                            'slug' => 'advisory_url',
                            'filter' => 'url',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'travel_before' => [
                            'title' => 'Travel Before',
                            'slug' => 'travel_before',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'travel_after' => [
                            'title' => 'Travel After',
                            'slug' => 'travel_after',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'affected_areas' => [
                            'title' => 'Affected Cities and Airports',
                            'slug' => 'affected_areas',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'waiver' => [
                            'title' => 'Waiver',
                            'slug' => 'waiver',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ],
                        'refund' => [
                            'title' => 'Refund',
                            'slug' => 'refund',
                            'filter' => 'paragraph',
                            'required' => false,
                            'additional_info' => true
                        ]
                    ],
                    'field_categories' => []
                ]
            ]
        ]

    ]

];
