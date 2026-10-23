<?php

/**
 * Site SMTP transport for WordPress wp_mail().
 * Set ZAPAL_SMTP_PASSWORD in the site's wp-config.php, never in this file.
 * Port 587 uses STARTTLS. Certificate verification is intentionally enabled.
 */
function WBZX_Zapal_configure_smtp($phpmailer): void
{
    $phpmailer->isSMTP();
    $phpmailer->Host = 'mail.zapal.ua';
    $phpmailer->Port = 587;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->SMTPAutoTLS = true;
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = 'no-reply@zapal.ua';
    $phpmailer->Password = defined('ZAPAL_SMTP_PASSWORD')
        ? (string)ZAPAL_SMTP_PASSWORD
        : '';
    $phpmailer->Timeout = 15;
    $phpmailer->SMTPDebug = 0;
}
add_action('phpmailer_init', 'WBZX_Zapal_configure_smtp');

/**
 * Prevent silent fallback to PHP mail() before the SMTP password is set.
 * WordPress and the enquiry form will correctly receive a failed send result.
 */
function WBZX_Zapal_require_smtp_password($pre, array $atts)
{
    if ($pre !== null) {
        return $pre;
    }

    if (!defined('ZAPAL_SMTP_PASSWORD') || !is_string(ZAPAL_SMTP_PASSWORD) || ZAPAL_SMTP_PASSWORD === '') {
        return false;
    }

    return null;
}
add_filter('pre_wp_mail', 'WBZX_Zapal_require_smtp_password', 10, 2);

// Use an address on the authenticated domain; visitor email stays Reply-To.
add_filter('wp_mail_from', static function ($from): string {
    return 'no-reply@zapal.ua';
});

add_filter('wp_mail_from_name', static function ($name): string {
    return 'ZAPAL';
});
