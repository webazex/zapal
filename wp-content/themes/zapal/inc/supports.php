<?php

add_action('after_setup_theme', function (): void {
    load_theme_textdomain('zapal', get_template_directory() . '/languages');

    add_theme_support('post-thumbnails', [
        'products',
        'recipes',
    ]);
});
