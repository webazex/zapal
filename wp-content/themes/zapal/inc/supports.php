<?php

add_action('after_setup_theme', function (): void {
    add_theme_support('post-thumbnails', [
        'products',
        'recipes',
    ]);
});