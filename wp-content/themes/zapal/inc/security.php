<?php

function zapal_security(): void {
    if (is_admin()) {
        return;
    }

    // WordPress version
    remove_action('wp_head', 'wp_generator');
    add_filter('the_generator', '__return_empty_string');

    // REST API discovery
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11);

    // REST API: authenticated users only
    add_filter('rest_authentication_errors', function ($result) {
        if (true === $result || is_wp_error($result)) {
            return $result;
        }

        if (!is_user_logged_in()) {
            return new WP_Error(
                'rest_forbidden',
                'REST API is disabled.',
                ['status' => 401]
            );
        }

        return $result;
    });

    // XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');
    add_filter('xmlrpc_methods', '__return_empty_array');

    // Pingbacks / trackbacks
    add_filter('pings_open', '__return_false', 20, 2);

    add_filter('wp_headers', function ($headers) {
        unset($headers['X-Pingback']);

        return $headers;
    });

    // Application Passwords
    add_filter('wp_is_application_passwords_available', '__return_false');

    // REST JSONP
    add_filter('rest_jsonp_enabled', '__return_false');
}

add_action('after_setup_theme', 'zapal_security');