<?php

function zapal_enqueue_assets() {
    wp_enqueue_style( 'zapal-css', get_stylesheet_uri() );
    get_template_directory_uri() . '/js/main.js'
        |> get_theme_file_path(...)
        |> (fn($x) => filemtime($x, ['in_footer' => true]))
        |> (fn($x) => wp_enqueue_script('zapal-js', get_template_directory_uri() . '/js/main.js', ['jquery'], $x));
}
add_action('wp_enqueue_scripts', 'zapal_enqueue_assets');