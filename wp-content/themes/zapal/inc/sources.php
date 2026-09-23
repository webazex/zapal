<?php

function zapal_enqueue_sources() {
    $jsPath = ASSET_DIR . 'js' . DIRECTORY_SEPARATOR . 'main.js';
    $jsUri  = ASSET_DIR_URI . 'js/main.js';
    wp_enqueue_style( 'zapal-css', get_stylesheet_uri() );
    wp_enqueue_script( 'zapal-js', $jsUri,
        ['jquery'],
        filemtime( $jsPath),
        ['in_footer' => true] );
}
add_action('wp_enqueue_scripts', 'zapal_enqueue_sources');