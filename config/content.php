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
            'title' => 'Airline Contact Information',
            'slug' => 'airline_contact',
            'association' => 'airline',
            'type' => ['content', 'link'],
            'fields' => [
                'email_agency' => [
                    'title' => 'Email',
                    'slug' => 'email_agency',
                    'filter' => 'email',
                    'multiple' => true,
                    'custom_sub_heading' => true,
                    'required' => false,
                    'additional_info' => true,
                    'category' => 'agency'
                ],
                'phone_agency' => [
                    'title' => 'Phone',
                    'slug' => 'phone_agency',
                    'filter' => 'phone',
                    'multiple' => true,
                    'custom_sub_heading' => true,
                    'required' => false,
                    'additional_info' => true,
                    'category' => 'agency'
                ],
                'site_agency' => [
                    'title' => 'Site',
                    'slug' => 'site_agency',
                    'filter' => 'url',
                    'multiple' => true,
                    'custom_sub_heading' => true,
                    'required' => false,
                    'additional_info' => true,
                    'category' => 'agency'
                ],
                'email_customer' => [
                    'title' => 'Email',
                    'slug' => 'email_customer',
                    'filter' => 'email',
                    'multiple' => true,
                    'custom_sub_heading' => true,
                    'required' => false,
                    'additional_info' => true,
                    'category' => 'customer'
                ],
                'phone_customer' => [
                    'title' => 'Phone',
                    'slug' => 'phone_customer',
                    'filter' => 'phone',
                    'multiple' => true,
                    'custom_sub_heading' => true,
                    'required' => false,
                    'additional_info' => true,
                    'category' => 'customer'
                ],
                'site_customer' => [
                    'title' => 'Site',
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
                    'title' => 'Agency Contacts (Internal)',
                    'slug' => 'agency',
                    'at_least_one' => true
                ],
                'customer' => [
                    'title' => 'Customer Contacts (External)',
                    'slug' => 'customer',
                    'at_least_one' => true
                ]
            ]
        ],
        'travel_advisories' => [
            'title' => 'Travel Advisories',
            'slug' => 'travel_advisories',
            'type' => ['content', 'link'],
            'association' => 'airline',
            'fields' => [
                'phone' => [
                    'title' => 'Phone',
                    'slug' => 'phone',
                    'filter' => 'phone',
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
                'email' => [
                    'title' => 'Email',
                    'slug' => 'email',
                    'filter' => 'email',
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
