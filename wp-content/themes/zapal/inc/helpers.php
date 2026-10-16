<?php

function WBZX_Zapal_get_section($section, array $data = []): void
{
    get_template_part('views/sections/' . $section, null, $data);
}

function WBZX_Zapal_get_blocks($block, array $data = []): void
{
    get_template_part('views/blocks/' . $block, null, $data);
}

function WBZX_Zapal_get_templates($template, array $data = []): void
{
    get_template_part('views/templates/' . $template, null, $data);
}

function WBZX_Zapal_render_section(array $data): void
{
    foreach ($data as $item) {
        $layoutKey = $item['acf_fc_layout'] ?? null;

        if (!$layoutKey) {
            continue;
        }

        WBZX_Zapal_get_section($layoutKey, $item);
    }
}

function WBZX_Zapal_get_query(array $args = []): object
{
    $defaultArgs = [
        'posts_per_page' => get_option('posts_per_page'),
    ];

    $args = wp_parse_args($args, $defaultArgs);

    return new WP_Query($args);
}

function WBZX_Zapal_get_products(array $args = []): array
{
    $args = wp_parse_args($args, [
        'post_type' => 'products',
    ]);

    $query = WBZX_Zapal_get_query($args);

    return $query->have_posts() ? $query->posts : [];
}

/**
 * Resolve product delivery settings according to the ACF toggle:
 * status = 1 -> use global deliveries from Site Settings;
 * status = 0 -> use deliveries stored in the product.
 */
function WBZX_Zapal_get_product_deliveries(array $acfData): array
{
    $deliveryGroup = $acfData['delivers-group'] ?? [];

    if (!is_array($deliveryGroup)) {
        return [];
    }

    if (!empty($deliveryGroup['status'])) {
        $globalDeliveries = get_field('deliveries', 'option');

        return is_array($globalDeliveries) ? $globalDeliveries : [];
    }

    $productDeliveries = $deliveryGroup['deliveries'] ?? [];

    return is_array($productDeliveries) ? $productDeliveries : [];
}

function WBZX_Zapal_products(array $args = []): array
{
    $result = [];
    $products = WBZX_Zapal_get_products($args);

    foreach ($products as $product) {
        $acfData = get_field('data-product', $product->ID);
        $acfData = is_array($acfData) ? $acfData : [];

        $categories = get_the_terms($product->ID, 'cproducts');
        if (is_wp_error($categories) || !$categories) {
            $categories = [];
        }

        $tags = get_the_terms($product->ID, 'ctags');
        if (is_wp_error($tags) || !$tags) {
            $tags = [];
        }

        $recipeImage = '';
        $recipe = $acfData['receipes'] ?? null;

        if ($recipe instanceof WP_Post) {
            $recipeImage = get_the_post_thumbnail_url($recipe->ID, 'large') ?: '';
        } elseif (is_numeric($recipe)) {
            $recipeImage = get_the_post_thumbnail_url((int)$recipe, 'large') ?: '';
        }

        $deliveryGroup = is_array($acfData['delivers-group'] ?? null)
            ? $acfData['delivers-group']
            : [];

        $productData = [
            'id' => $product->ID,
            'name' => $product->post_title,
            'content' => $product->post_content,
            'permalink' => get_permalink($product->ID),
            'thumbnail' => get_the_post_thumbnail($product->ID, 'large', ['class' => 'some']) ?: false,
            'thumbnail_url' => get_the_post_thumbnail_url($product->ID, 'large') ?: '',
            'recipe_image_url' => $recipeImage,
            'meta' => !empty($acfData) ? $acfData : false,
            'category' => $categories,
            'tags' => $tags,
            'deliveries' => WBZX_Zapal_get_product_deliveries($acfData),
            'uses_global_deliveries' => !empty($deliveryGroup['status']),
        ];

        $result[$product->ID] = WBZX_Zapal_mapping_product($productData);
    }

    return $result;
}

/**
 * Sanitizes ACF WYSIWYG content intended to be rendered inside H1/H2.
 *
 * Allowed editor markup:
 * - line breaks
 * - bold
 * - italic / serif accent
 * - text color
 *
 * Only the CSS "color" property is allowed on spans.
 */
function WBZX_Zapal_heading_content($content): string
{
    if (!is_string($content) || trim($content) === '') {
        return '';
    }

    $headingSafeCss = static function (array $styles): array {
        return ['color'];
    };

    add_filter('safe_style_css', $headingSafeCss);

    try {
        $content = wp_kses($content, [
            'p' => [],
            'br' => [],
            'em' => [],
            'i' => [],
            'strong' => [],
            'b' => [],
            'span' => [
                'style' => true,
            ],
        ]);
    } finally {
        remove_filter('safe_style_css', $headingSafeCss);
    }

    $content = preg_replace('/<\/p>\s*<p>/i', '<br>', $content);
    $content = str_replace(['<p>', '</p>'], '', $content);

    $content = str_replace(
        ['<em>', '</em>', '<i>', '</i>'],
        ['<span class="serif">', '</span>', '<span class="serif">', '</span>'],
        $content
    );

    return trim($content);
}
