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

function zapal_enqueue_sources()
{
    $cssPath = get_stylesheet_directory() . '/style.css';
    $cssVersion = is_file($cssPath) ? (string)filemtime($cssPath) : null;

    $jsPath = WBZX_ZAPAL_ASSET_DIR . 'js' . DIRECTORY_SEPARATOR . 'main.js';
    $jsVersion = is_file($jsPath) ? (string)filemtime($jsPath) : null;
    $jsUri = WBZX_ZAPAL_ASSET_DIR_URI . 'js/main.js';

    wp_enqueue_style('zapal-css', get_stylesheet_uri(), [], $cssVersion);

    wp_enqueue_script(
        'zapal-js',
        $jsUri,
        ['jquery'],
        $jsVersion,
        ['in_footer' => true]
    );

    $darkmodeEnabled = function_exists('get_field')
        ? (bool)get_field('darkmode', 'option')
        : false;

    if (!$darkmodeEnabled) {
        wp_add_inline_script(
            'zapal-js',
            "try{window.localStorage.removeItem('zapal-theme');}catch(e){}",
            'before'
        );
    }
}

add_action('wp_enqueue_scripts', 'zapal_enqueue_sources');
