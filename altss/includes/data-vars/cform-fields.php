<?php
defined( 'ABSPATH' ) || exit;

$FORM_FIELDS = [
    'email' => [
        'type' => 'email',
        'label' => esc_html__( "Email address", "altss" ),
        'placeholder' => esc_html__( "Enter your email address", "altss" )
    ],
    'phone' => [
        'type' => 'text',
        'label' =>__( "Phone number", "altss" ),
        'placeholder' => esc_html__( "Enter your phone number", "altss" )
    ],
    'fname' => [
        'type' => 'text',
        'label' => esc_html__( "First Name", "altss" ),
        'placeholder' => esc_html__( "Enter your First Name", "altss" )
    ],
    'sname' => [
        'type' => 'text',
        'label' => esc_html__( "Last Name", "altss" ),
        'placeholder' => esc_html__( "Enter your Last Name", "altss" )
    ],
    'city' => [
        'type' => 'text',
        'label' => esc_html__( "City", "altss" ),
        'placeholder' => esc_html__( "Enter your city", "altss" )
    ],
    'website' => [
        'type' => 'url',
        'label' => esc_html__( "Website URL", "altss" ),
        'placeholder' => esc_html__( "Enter your Website URL", "altss" )
    ],
    'message' => [
        'type' => 'textarea',
        'label' => esc_html__( "Message text", "altss" ),
        'placeholder' => esc_html__( "Enter the text of your message", "altss" )
    ],
];
?>