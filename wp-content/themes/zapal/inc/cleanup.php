<?php

function zapal_cleanup_wp(): void {
    if (is_admin()) {
        return;
    }

    // Emoji
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    add_filter('emoji_svg_url', '__return_false');

    // Head garbage
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('template_redirect', 'wp_shortlink_header', 11);
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11);

    // oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // jQuery Migrate
    add_filter('wp_default_scripts', function ($scripts) {
        if (isset($scripts->registered['jquery'])) {
            $scripts->registered['jquery']->deps = array_diff(
                $scripts->registered['jquery']->deps,
                ['jquery-migrate']
            );
        }
    });

    // Frontend assets
    add_action('wp_enqueue_scripts', function () {
        wp_dequeue_script('wp-embed');

        if (!is_user_logged_in()) {
            wp_dequeue_style('dashicons');
        }
    }, 100);
}

add_action('after_setup_theme', 'zapal_cleanup_wp');