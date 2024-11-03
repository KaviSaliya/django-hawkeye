<?php
return [
    'banner_section_1' => [
        'single' => [
            'field_name' => [
                'heading_1' => 'text',
                'short_description' => 'text',
                'button_name' => 'text',
                'button_link' => 'url',
                'banner_right_image_1' => 'file',
                'heading_2' => 'text',
                'short_text' => 'text',
                'banner_right_image_2' => 'file',
                'heading_3' => 'text',
                'short_text_2' => 'text',
                'heading_4' => 'text',
                'short_text_3' => 'text',
                'heading_5' => 'text',
                'short_text_4' => 'text',
                'number_of_ratings' => 'number',
                'client_image_1' => 'file',
                'client_image_2' => 'file',
                'client_image_3' => 'file',
                'client_image_4' => 'file',
            ],
            'validation' => [
                'heading_1.*' => 'required|max:80',
                'short_description.*' => 'required|max:250',
                'button_name.*' => 'required|max:50',
                'button_link.*' => 'nullable',
                'banner_right_image_1.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'heading_2.*' => 'required|max:40',
                'short_text.*' => 'required|max:50',
                'banner_right_image_2.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'heading_3.*' => 'required|max:40',
                'short_text_2.*' => 'required|max:50',
                'heading_4.*' => 'required|max:40',
                'short_text_3.*' => 'required|max:50',
                'heading_5.*' => 'required|max:40',
                'short_text_4.*' => 'required|max:50',
                'number_of_ratings.*' => 'nullable|numeric',
                'client_image_1.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'client_image_2.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'client_image_3.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'client_image_4.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'partner_logo' => 'file',
            ],
            'validation' => [
                'partner_logo.*' => 'required|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/banner_1.png'

    ],

    'banner_section_2' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
                'button_name' => 'text',
                'button_link' => 'url',
                'video_link' => 'url',
                'background_image' => 'file',
                'banner_right_image' => 'file',
            ],
            'validation' => [
                'heading' => 'required | max:50',
                'short_description' => 'required | max:200',
                'button_name' => 'required | max:30',
                'button_link' => 'nullable',
                'video_link' => 'nullable',
                'background_image' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'banner_right_image' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/banner_2.png'
    ],

    'about_section_2' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
                'button_name' => 'text',
                'button_link' => 'url',
                'banner_image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'required|max:250',
                'button_name.*' => 'required|max:50',
                'button_link.*' => 'nullable',
                'banner_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'image' => 'file',
            ],
            'validation' => [
                'image.*' => 'required|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/about_2.png'
    ],

    'how_work_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'nullable|max:250',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
                'icon_image' => 'file'
            ],
            'validation' => [
                'heading.*' => 'required|max:60',
                'short_description.*' => 'nullable|max:250',
                'icon_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/how_work.png'
    ],

    'position_apart_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
                'image' => 'file',
                'background_image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'nullable|max:250',
                'image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'background_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
                'icon_image' => 'file'
            ],
            'validation' => [
                'heading.*' => 'required|max:60',
                'short_description.*' => 'nullable|max:250',
                'icon_image.*' =>'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/position_apart.png'
    ],
    'project_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
                'button_name' => 'text',
                'button_link' => 'url',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'nullable|max:250',
                'button_name.*' => 'required|max:50',
                'button_link.*' => 'nullable',
            ]
        ],
        'prieview' => 'assets/global/images/project_section.png'
    ],


    'agriculture_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
                'heading_2' => 'text',
                'sub_heading' => 'text',
                'short_text' => 'text',
                'image' => 'file',

            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'required|max:250',
                'heading_2.*' => 'required|max:100',
                'sub_heading.*' => 'nullable|max:100',
                'short_text.*' => 'required|max:300',
                'image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',

            ]
        ],
        'multiple' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'nullable|max:250',
            ]
        ],
        'prieview' => 'assets/global/images/agriculture_section.png'
    ],

    'pricing_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'nullable|max:250',
            ]
        ],
        'prieview' => 'assets/global/images/pricing_section.png'
    ],
    'pricing_section_2' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_text.*' => 'nullable|max:200',
            ]
        ],
        'prieview' => 'assets/global/images/pricing_section_2.png'
    ],

    'farming_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'nullable|max:250',
            ]
        ],
        'prieview' => 'assets/global/images/farming_section.png'
    ],

    'testimonial_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'nullable|max:250',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'investor_name' => 'text',
                'position' => 'text',
                'heading' => 'text',
                'short_text' => 'text',
                'rating' => 'number',
                'image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'investor_name.*' => 'required|max:50',
                'position.*' => 'nullable|max:70',
                'short_text.*' => 'nullable|max:300',
                'image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'rating.*' => 'required|numeric|between:1,5',
            ]
        ],
        'prieview' => 'assets/global/images/testimonial_section.png'
    ],

    'blog_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'text',
                'button_name' => 'text',
                'button_link' => 'url',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_description.*' => 'nullable|max:250',
                'button_name.*' => 'required|max:50',
                'button_link.*' => 'nullable',
            ]
        ],
        'prieview' => 'assets/global/images/blog_section.png'
    ],

    'blog_section_2' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
                'button_name' => 'text',
                'button_link' => 'url'
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_text.*' => 'nullable|max:250',
                'button_name.*' => 'required|max:50',
                'button_link.*' => 'nullable',
            ]
        ],
        'prieview' => 'assets/global/images/blog_section_2.png'
    ],

    'counter_section' => [
        'multiple' => [
            'field_name' => [
                'countable_item_name' => 'text',
                'prefix' => 'text',
                'count' => 'number',
            ],
            'validation' => [
                'countable_item_name.*' => 'required|max:60',
                'prefix.*' => 'required|max:10',
                'count.*' => 'required|numeric',
            ]
        ],
        'prieview' => 'assets/global/images/counter_section.png'
    ],
    'counter_section_2' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:50',
                'short_text.*' => 'nullable|max:200',
                'background_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'countable_item_name' => 'text',
                'prefix' => 'text',
                'count' => 'number',
            ],
            'validation' => [
                'countable_item_name.*' => 'required|max:60',
                'prefix.*' => 'required|max:10',
                'count.*' => 'required|numeric',
            ]
        ],
        'prieview' => 'assets/global/images/counter_section_2.png'
    ],

    'subscribe_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
                'button_name' => 'text',
                'button_link' => 'url',
                'image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:80',
                'short_text' => 'required | max:200',
                'button_name.*' => 'required|max:50',
                'button_link.*' => 'nullable',
                'image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/subscribe_section.png'
    ],


    'investment_way_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:80',
                'short_text.*' => 'required|max:250',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
                'icon_image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:60',
                'icon_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'short_text.*' => 'required|max:250',
            ]
        ],
        'prieview' => 'assets/global/images/investment_way_section.png'
    ],

    'product_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
                'button_name' => 'text',
                'button_link' => 'url',
            ],
            'validation' => [
                'heading.*' => 'required|max:60',
                'short_text.*' => 'required|max:250',
                'button_name.*' => 'required|max:50',
                'button_link.*' => 'nullable',
            ]
        ],
        'prieview' => 'assets/global/images/product_section.png'
    ],

    'investor_review_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:60',
                'short_text.*' => 'required|max:200',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'investor_name' => 'text',
                'position' => 'text',
                'rating' => 'number',
                'review' => 'textarea',
                'investor_image' => 'file',
            ],
            'validation' => [
                'investor_name.*' => 'required|max:60',
                'position.*' => 'nullable|max:70',
                'rating.*' => 'nullable|numeric|between:1,5',
                'review.*' => 'required',
                'investor_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/investor_review_section.png'
    ],

    'about_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_description' => 'textarea',
                'about_image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:60',
                'short_description.*' => 'required|max:800',
                'about_image.*' => 'nullable|max:5000',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'button_name' => 'text',
                'button_link' => 'url',
                'icon' => 'text'
            ],
            'validation' => [
                'button_name.*' => 'required|max:60',
                'button_link.*' => 'nullable',
                'icon' => 'required'
            ]
        ],
        'prieview' => 'assets/global/images/about_section.png'
    ],

    'faq_section' => [
        'single' => [
            'field_name' => [
                'faq_image' => 'file',
            ],
            'validation' => [
                'faq_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'question' => 'text',
                'answer' => 'textarea',
            ],
            'validation' => [
                'question.*' => 'required|max:300',
                'answer.*' => 'required|max:1000',
            ]
        ],
        'prieview' => 'assets/global/images/faq_section.png'
    ],

    'contact_section' => [
        'single' => [
            'field_name' => [
                'contact_image' => 'file'
            ],
            'validation' => [
                'contact_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/contact_section.png'
    ],

    'address_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'short_text' => 'text',
                'address' => 'text',
                'email' => 'text',
                'phone' => 'number',
                'location_url' => 'url',
                'address_page_image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:80',
                'short_text.*' => 'required|max:250',
                'address.*' => 'required|max:250',
                'email.*' => 'required|email',
                'phone.*' => 'required|numeric',
                'location_url.*' => 'nullable',
                'address_page_image' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ],
        'prieview' => 'assets/global/images/address_section.png'
    ],

    'privacy_policy' => [
        'multiple' => [
            'field_name' => [
                'heading' => 'text',
                'description' => 'textarea',
            ],
            'validation' => [
                'heading.*' => 'required|max:80',
                'description.*' => 'required',
            ]
        ],
        'prieview' => 'assets/global/images/privacy_policy.png'
    ],

    'terms_condition' => [
        'multiple' => [
            'field_name' => [
                'heading' => 'text',
                'description' => 'textarea',
            ],
            'validation' => [
                'heading.*' => 'required|max:80',
                'description.*' => 'required',
            ]
        ],
        'prieview' => 'assets/global/images/privacy_policy.png'
    ],

    'cookie_policy' => [
        'multiple' => [
            'field_name' => [
                'heading' => 'text',
                'description' => 'textarea',
            ],
            'validation' => [
                'heading.*' => 'required|max:80',
                'description.*' => 'required',
            ]
        ],
        'prieview' => 'assets/global/images/privacy_policy.png'
    ],

    'login_registration' => [
        'single' => [
            'field_name' => [
                'login_heading' => 'text',
                'login_subheading' => 'text',
                'register_heading' => 'text',
                'register_subheading' => 'text',
                'login_page_image' => 'file',
                'register_page_image' => 'file',
            ],
            'validation' => [
                'login_heading.*' => 'required|max:80',
                'login_subheading.*' => 'required|max:150',
                'register_heading.*' => 'required|max:80',
                'register_subheading.*' => 'required|max:150',
                'login_page_image.*' => 'nullable|max:5000|image|mimes:jpeg,png,jpg',
                'register_page_image.*' =>  'nullable|max:5000|image|mimes:jpeg,png,jpg',
            ]
        ]
    ],


    'footer_section' => [
        'single' => [
            'field_name' => [
                'address' => 'text',
                'phone_number' => 'number',
                'subscribe_heading' => 'text',
                'subscribe_text' => 'text',
            ],
            'validation' => [
                'address.*' => 'nullable',
                'phone_number.*' => 'nullable|numeric',
                'subscribe_heading.*' => 'required|max:60',
                'subscribe_text.*' => 'required|max:60',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'icon' => 'text',
                'link' => 'url',
            ],
            'validation' => [
                'icon.*' => 'nullable',
                'link.*' => 'nullable',
            ]
        ],
        'prieview' => 'assets/global/images/footer_section.png'
    ],

    'top_section' => [
        'single' => [
            'field_name' => [
                'address' => 'text',
                'email' => 'text',
            ],
            'validation' => [
                'email' => 'required',
                'address' => 'required',
            ]
        ],
        'prieview' => 'assets/global/images/top_section.png'
    ],



    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
        'integer' => 'This field must be an integer value',
    ],

    'content_media' => [
        'image' => 'file',
        'thumb_image' => 'file',
        'my_link' => 'url',
        'button_link' => 'url',
        'icon' => 'icon',
        'location_url' => 'url',
        'count_number' => 'number',
        'phone' => 'number',
        'phone_number' => 'number',
        'start_date' => 'date',
        'background_layer' => 'file',
        'banner_right_image_1' => 'file',
        'banner_right_image_2' => 'file',
        'banner_right_image' => 'file',
        'client_image_1' => 'file',
        'client_image_2' => 'file',
        'client_image_3' => 'file',
        'client_image_4' => 'file',
        'partner_logo' => 'file',
        'icon_image' => 'file',
        'image_1' => 'file',
        'image_2' => 'file',
        'section_shape_image' => 'file',
        'background_image' => 'file',
        'rating' => 'number',
        'count' => 'number',
        'background_layer_1' => 'file',
        'background_layer_2' => 'file',
        'banner_image' => 'file',
        'investor_image' => 'file',
        'about_image' => 'file',
        'faq_image' => 'file',
        'contact_image' => 'file',
        'address_page_image' => 'file',
        'login_page_image' => 'file',
        'register_page_image' => 'file',
        'number_of_ratings' => 'number',
        'link' => 'url',
        'video_link' => 'url'

    ]
];

