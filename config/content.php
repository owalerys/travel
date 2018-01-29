<?php

return [

    'categories' => [
        'schedule_changes' => [
            'title' => 'Schedule Changes',
            'slug' => 'schedule_changes',
            'type' => 'content',
            'association' => 'airline',
            'fields' => [
                'detail' => [
                    'title' => 'Contact Point',
                    'slug' => 'detail',
                    'filter' => 'contact',
                    'multiple' => true,
                    'custom_title' => true,
                    'required' => true,
                    'additional_info' => true
                ]
            ],
            'field_categories' => []
        ],
        'name_corrections' => [
            'title' => 'Name Corrections',
            'slug' => 'name_corrections',
            'type' => 'content',
            'association' => 'airline',
            'fields' => [
                'detail' => [
                    'title' => 'Contact Point',
                    'slug' => 'detail',
                    'filter' => 'contact',
                    'multiple' => true,
                    'custom_title' => true,
                    'required' => true,
                    'additional_info' => true
                ]
            ],
            'field_categories' => []
        ],
        'airline_contact' => [
            'title' => 'Airline Contact',
            'slug' => 'airline_contact',
            'association' => 'airline',
            'categories' => [
                'agency_assistance' => [
                    'title' => 'Agency Assistance',
                    'slug' => 'agency_assistance',
                    'association' => 'airline',
                    'type' => 'content',
                    'fields' => [
                        'detail' => [
                            'title' => 'Contact Point',
                            'slug' => 'detail',
                            'filter' => 'contact',
                            'multiple' => true,
                            'custom_title' => true,
                            'required' => true,
                            'additional_info' => true
                        ]
                    ],
                    'field_categories' => [
                        'cat1' => [
                            'title' => 'ABC',
                            'slug' => 'cat1'
                        ],
                        'cat2' => [
                            'title' => 'XYZ',
                            'slug' => 'cat2'
                        ]
                    ]
                ],
                'reservations' => [
                    'title' => 'Reservations',
                    'slug' => 'reservations',
                    'association' => 'airline',
                    'type' => 'content',
                    'fields' => [
                        'detail' => [
                            'title' => 'Contact Point',
                            'slug' => 'detail',
                            'filter' => 'contact',
                            'multiple' => true,
                            'custom_title' => true,
                            'required' => true,
                            'additional_info' => true
                        ]
                    ],
                    'field_categories' => []
                ]
            ]
        ],
        'travel_advisories' => [
            'title' => 'Travel Advisories',
            'slug' => 'travel_advisories',
            'type' => ['content', 'link'],
            'association' => '',
            'fields' => [
                'detail' => [
                    'title' => 'Contact Point',
                    'slug' => 'detail',
                    'filter' => 'contact',
                    'multiple' => true,
                    'custom_title' => true,
                    'required' => true,
                    'additional_info' => true
                ]
            ],
            'field_categories' => []
        ],
        'baggage_fees' => [
            'title' => 'Baggage Fees',
            'slug' => 'baggage_fees',
            'association' => 'airline',
            'type' => ['content', 'link'],
            'fields' => [
                'detail' => [
                    'title' => 'Contact Point',
                    'slug' => 'detail',
                    'filter' => 'contact',
                    'multiple' => true,
                    'custom_title' => true,
                    'required' => true,
                    'additional_info' => true
                ]
            ],
            'field_categories' => []
        ]
    ]

];
