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
 * Site-wide delivery options share images and store UA/RU/EN text in one row.
 * Existing delivery-text values remain the Ukrainian source text.
 * The frontend view always receives the same delivery-text key.
 */
function WBZX_Zapal_get_global_deliveries(): array
{
    if (!function_exists('get_field')) {
        return [];
    }

    $deliveries = get_field('deliveries', 'option');
    if (!is_array($deliveries)) {
        return [];
    }

    $language = function_exists('pll_current_language')
        ? (string)pll_current_language('slug')
        : (string)get_locale();

    $language = strtolower(substr($language, 0, 2));
    $textField = match ($language) {
        'ru' => 'delivery-text-ru',
        'en' => 'delivery-text-en',
        default => 'delivery-text',
    };

    $result = [];
    foreach ($deliveries as $delivery) {
        if (!is_array($delivery)) {
            continue;
        }

        $localizedText = trim((string)($delivery[$textField] ?? ''));
        if ($localizedText === '' && $textField !== 'delivery-text') {
            $localizedText = trim((string)($delivery['delivery-text'] ?? ''));
        }

        $delivery['delivery-text'] = $localizedText;
        $result[] = $delivery;
    }

    return $result;
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
        return WBZX_Zapal_get_global_deliveries();
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


/** Homepage of the currently selected language, with no Polylang dependency. */
function WBZX_Zapal_home_url(): string
{
    if (function_exists('pll_home_url')) {
        $url = pll_home_url();
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    return home_url('/');
}

/** The published catalogue page assigned to this language. */
function WBZX_Zapal_catalog_page(): ?WP_Post
{
    static $cache = [];

    $language = function_exists('pll_current_language')
        ? (string)pll_current_language('slug')
        : '';

    if (array_key_exists($language, $cache)) {
        return $cache[$language];
    }

    $query = [
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-catalog.php',
        'suppress_filters' => false,
    ];

    if ($language !== '') {
        $query['lang'] = $language;
    }

    $pages = get_posts($query);
    $page = $pages[0] ?? null;

    // Compatibility with existing installations using /catalog/ as the source page.
    if (!$page instanceof WP_Post) {
        $source = get_page_by_path('catalog');
        if ($source instanceof WP_Post) {
            $id = $source->ID;
            if ($language !== '' && function_exists('pll_get_post')) {
                $id = (int)pll_get_post($id, $language);
            }
            $page = $id && get_post_status($id) === 'publish' ? get_post($id) : null;
        }
    }

    return $cache[$language] = $page instanceof WP_Post ? $page : null;
}

function WBZX_Zapal_catalog_url(): string
{
    $page = WBZX_Zapal_catalog_page();
    return $page ? get_permalink($page->ID) : WBZX_Zapal_home_url();
}

/** Resolve a source page slug to its current-language translation, if published. */
function WBZX_Zapal_localized_page_url(string $sourceSlug): string
{
    $source = get_page_by_path($sourceSlug);
    if (!$source instanceof WP_Post) {
        return WBZX_Zapal_home_url();
    }

    $id = (int)$source->ID;
    if (function_exists('pll_current_language') && function_exists('pll_get_post')) {
        $language = pll_current_language('slug');
        if (is_string($language) && $language !== '') {
            $id = (int)pll_get_post($id, $language);
        }
    }

    return $id && get_post_status($id) === 'publish'
        ? get_permalink($id)
        : WBZX_Zapal_home_url();
}


/** Text used by the custom header and product breadcrumbs. */
function WBZX_Zapal_nav_labels(): array
{
    $language = function_exists('pll_current_language')
        ? (string)pll_current_language('slug')
        : strtolower(substr((string)get_locale(), 0, 2));

    $labels = [
        'uk' => [
            'home' => 'Головна', 'catalog' => 'Продукція', 'about' => 'Про нас',
            'contact' => 'Задати питання', 'navigation' => 'Головна навігація', 'language' => 'Мова сайту',
        ],
        'ru' => [
            'home' => 'Главная', 'catalog' => 'Продукция', 'about' => 'О нас',
            'contact' => 'Задать вопрос', 'navigation' => 'Главная навигация', 'language' => 'Язык сайта',
        ],
        'en' => [
            'home' => 'Home', 'catalog' => 'Products', 'about' => 'About us',
            'contact' => 'Contact us', 'navigation' => 'Main navigation', 'language' => 'Site language',
        ],
    ];

    return $labels[$language] ?? $labels['uk'];
}
