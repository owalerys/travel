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
                            'at_least_one' => true
                        ],
                        'customer' => [
                            'title' => 'Customer Contacts',
                            'slug' => 'customer',
                            'at_least_one' => true
                        ]
                    ]
                ],
                'schedule_changes' => [
                    'title' => 'Schedule Changes',
                    'slug' => 'schedule_changes',
                    'association' => 'airline',
                    'type' => ['content', 'link'],
                    'accept_new_submissions' => true,
                    'fields' => [
                        'exchange_class_of_booking' => [
                            'title' => 'Class of Booking',
                            'slug' => 'exchange_class_of_booking',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => true,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_rerouting' => [
                            'title' => 'Rerouting',
                            'slug' => 'exchange_rerouting',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => true,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_travel_window' => [
                            'title' => 'Travel Window',
                            'slug' => 'exchange_travel_window',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => true,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_endorsement_box' => [
                            'title' => 'Endorsement Box',
                            'slug' => 'exchange_endorsement_box',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => true,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_osi' => [
                            'title' => 'OSI',
                            'slug' => 'exchange_osi',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => true,
                            'additional_info' => true,
                            'category' => 'exchange'
                        ],
                        'exchange_tour_code' => [
                            'title' => 'Tour Code',
                            'slug' => 'exchange_tour_code',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => true,
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
                            'category' => 'exchange'
                        ],
                        'refund_tour_code' => [
                            'title' => 'Tour Code',
                            'slug' => 'refund_tour_code',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => true,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'refund_remark' => [
                            'title' => 'Remark',
                            'slug' => 'refund_remark',
                            'filter' => 'paragraph',
                            'multiple' => false,
                            'required' => true,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'refund_misc' => [
                            'title' => 'Additional Info',
                            'slug' => 'refund_misc',
                            'filter' => 'paragraph',
                            'multiple' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'refund'
                        ],
                        'contact_phone' => [
                            'title' => 'Phone Numbers',
                            'slug' => 'contact_phone',
                            'filter' => 'phone',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'contact'
                        ],
                        'contact_email' => [
                            'title' => 'Email Addresses',
                            'slug' => 'contact_email',
                            'filter' => 'email',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'contact'
                        ],
                        'contact_site' => [
                            'title' => 'Airline Sites',
                            'slug' => 'contact_site',
                            'filter' => 'url',
                            'multiple' => true,
                            'custom_sub_heading' => true,
                            'required' => false,
                            'additional_info' => true,
                            'category' => 'contact'
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
                        ],
                        'contact' => [
                            'title' => 'Airline Contacts for Assistance',
                            'slug' => 'contact'
                        ]
                    ]
                ]
            ]
        ]

    ]

];
