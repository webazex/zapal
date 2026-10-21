<?php

/**
 * Accept B2B enquiries from both guests and logged-in users.
 * The email recipient is always resolved server-side, never from POST data.
 */
function zapal_ajax_form(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        wp_send_json_error([
            'message' => __('Метод запиту не підтримується.', 'zapal'),
        ], 405);
    }

    if (!check_ajax_referer('zapal-ajax-nonce', 'nonce', false)) {
        wp_send_json_error([
            'message' => __('Сторінка застаріла. Оновіть її та повторіть спробу.', 'zapal'),
        ], 403);
    }

    // Honeypot: real visitors never fill this field.
    $honeypot = $_POST['company_site'] ?? '';
    if (!is_string($honeypot) || trim($honeypot) !== '') {
        wp_send_json_error([
            'message' => __('Не вдалося обробити запит.', 'zapal'),
        ], 400);
    }

    $input = static function (string $key): string {
        $value = wp_unslash($_POST[$key] ?? '');
        return is_string($value) ? trim($value) : '';
    };

    $productId = absint($input('product'));
    $name = sanitize_text_field($input('name'));
    $phone = sanitize_text_field($input('phone'));
    $email = sanitize_email($input('email'));
    $message = sanitize_textarea_field($input('message'));
    $errors = [];

    $product = $productId ? get_post($productId) : null;
    if (!$product instanceof WP_Post || $product->post_type !== 'products' || $product->post_status !== 'publish') {
        $errors['product'] = __('Оберіть доступний продукт.', 'zapal');
    }

    if ($name === '' || strlen($name) > 240) {
        $errors['name'] = __('Вкажіть ваше ім’я (не більше 120 символів).', 'zapal');
    }

    $phoneDigits = preg_replace('/\D+/', '', $phone);
    if ($phone === '' || strlen($phone) > 32 || strlen($phoneDigits) < 7 || strlen($phoneDigits) > 16) {
        $errors['phone'] = __('Вкажіть коректний номер телефону.', 'zapal');
    }

    if ($input('email') !== '' && (!is_email($email) || strlen($email) > 254)) {
        $errors['email'] = __('Вкажіть коректну адресу електронної пошти.', 'zapal');
    }

    if ($message === '' || strlen($message) > 8000) {
        $errors['message'] = __('Вкажіть питання (не більше 2000 символів).', 'zapal');
    }

    if ($errors) {
        wp_send_json_error([
            'message' => __('Перевірте, будь ласка, введені дані.', 'zapal'),
            'errors' => $errors,
        ], 422);
    }

    // WP option contacts is a flexible-content list; only email rows are valid recipients.
    $recipient = 'zapalsauce@gmail.com';
    if (function_exists('get_field')) {
        $contacts = get_field('contacts', 'option');
        if (is_array($contacts)) {
            foreach ($contacts as $item) {
                if (!is_array($item) || ($item['acf_fc_layout'] ?? '') !== 'email') {
                    continue;
                }
                $candidate = sanitize_email((string)($item['email'] ?? ''));
                if (is_email($candidate)) {
                    $recipient = $candidate;
                    break;
                }
            }
        }
    }

    $subject = sprintf(__('ZAPAL: новий запит щодо %s', 'zapal'), $product->post_title);
    $body = implode("\n", [
        __('Нове звернення із сайту ZAPAL', 'zapal'),
        '',
        __('Продукт:', 'zapal') . ' ' . $product->post_title . ' (#' . $productId . ')',
        __('Ім’я:', 'zapal') . ' ' . $name,
        __('Телефон:', 'zapal') . ' ' . $phone,
        'Email: ' . ($email ?: '—'),
        '',
        __('Питання:', 'zapal'),
        $message,
        '',
        __('Сайт:', 'zapal') . ' ' . home_url('/'),
    ]);

    $headers = ['Content-Type: text/plain; charset=UTF-8'];
    if ($email !== '') {
        $headers[] = 'Reply-To: ' . $email;
    }

    if (!wp_mail($recipient, $subject, $body, $headers)) {
        wp_send_json_error([
            'message' => __('Не вдалося відправити запит. Спробуйте пізніше або зв’яжіться з нами напряму.', 'zapal'),
        ], 500);
    }

    wp_send_json_success([
        'title' => __('Дякуємо! Запит отримано.', 'zapal'),
        'message' => __('Ми отримали ваше звернення та зв’яжемося з вами.', 'zapal'),
    ]);
}

add_action('wp_ajax_zapal-form', 'zapal_ajax_form');
add_action('wp_ajax_nopriv_zapal-form', 'zapal_ajax_form');
