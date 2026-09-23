<?php

function zapal_get_sources() {
    wp_enqueue_style( 'zapal-css', get_stylesheet_uri() );
    wp_enqueue_script( 'zapal-js', get_template_directory_uri() . '/js/main.js',
        ['jquery'],
        filemtime( get_template_directory_uri() . '/js/main.js'),
        ['in_footer' => true] );
}
add_action('wp_enqueue_scripts', 'zapal_get_sources');