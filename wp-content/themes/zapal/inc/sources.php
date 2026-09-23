<?php
add_action('wp_default_scripts', function ($scripts) {
    if (is_admin()) {
        return;
    }

    if (isset($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            ['jquery-migrate']
        );
    }

    $scripts->add_data('jquery', 'group', 1);
    $scripts->add_data('jquery-core', 'group', 1);
});
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