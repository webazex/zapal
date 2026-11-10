<?php

function WBZX_Zapal_acf_heading_toolbars(array $toolbars): array
{
    $toolbars['ZAPAL Heading'] = [
        1 => [
            'bold',
            'italic',
            'forecolor',
            'removeformat',
            'undo',
            'redo',
        ],
    ];

    return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars', 'WBZX_Zapal_acf_heading_toolbars');

/**
 * Migration bridge: legacy product post_content can be transferred to ACF
 * without editing the database during deployment. The first product save
 * persists the WYSIWYG field as part of the regular ACF save process.
 *
 * An existing (even intentionally empty) ACF value always wins.
 */
function WBZX_Zapal_acf_load_product_story($value, $postId, array $field)
{
    $productId = is_numeric($postId) ? absint($postId) : 0;
    if (!$productId && is_string($postId) && preg_match('/^post_(\d+)$/', $postId, $matches)) {
        $productId = absint($matches[1]);
    }

    if (!$productId || get_post_type($productId) !== 'products') {
        return $value;
    }

    if (metadata_exists('post', $productId, 'data-product_info_story-content')) {
        return $value;
    }

    $post = get_post($productId);

    return $post instanceof WP_Post && is_string($post->post_content)
        ? $post->post_content
        : $value;
}
add_filter(
    'acf/load_value/key=field_zapal_product_story_content_20261023',
    'WBZX_Zapal_acf_load_product_story',
    10,
    3
);


// The initial palette can be configured independently of the theme switcher.
add_action('acf/init', function (): void {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_zapal_theme_default_20261110',
        'title' => 'Оформлення сайту',
        'fields' => [
            [
                'key' => 'field_zapal_default_theme_20261110',
                'label' => 'Тема за замовчуванням',
                'name' => 'default_theme',
                'type' => 'select',
                'choices' => [
                    'dark' => 'Темна',
                    'light' => 'Світла',
                ],
                'default_value' => 'dark',
                'return_format' => 'value',
                'ui' => 1,
                'instructions' => 'Працює лише якщо увімкнено перемикання тем. Якщо вимкнено, сайт завжди темний. Збережений вибір відвідувача має пріоритет.',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'site-settings',
                ],
            ],
        ],
        'active' => true,
    ]);
});
