<?php

add_action('after_setup_theme', function (): void {
    load_theme_textdomain('zapal', get_template_directory() . '/languages');

    // Let WordPress and Yoast SEO output the document title through wp_head().
    add_theme_support('title-tag');

    add_theme_support('post-thumbnails', [
        'products',
        'recipes',
    ]);
});
