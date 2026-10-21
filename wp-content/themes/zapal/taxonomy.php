<?php

get_header();

$term = get_queried_object();

// Find the catalog page for the active language, regardless of its URL slug.
$pageQuery = [
    'post_type' => 'page',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'meta_key' => '_wp_page_template',
    'meta_value' => 'template-catalog.php',
    'suppress_filters' => false,
];

if (function_exists('pll_current_language')) {
    $pageQuery['lang'] = pll_current_language('slug');
}

$catalogPages = get_posts($pageQuery);
$catalogPage = $catalogPages[0] ?? null;
$catalogId = $catalogPage instanceof WP_Post ? $catalogPage->ID : 0;
$catalogKicker = ($catalogId && function_exists('get_field'))
    ? get_field('catalog-kicker', $catalogId)
    : '';
$catalogUrl = $catalogId ? get_permalink($catalogId) : home_url('/catalog/');
?>
<main>
    <?php
    if ($term instanceof WP_Term && in_array($term->taxonomy, ['cproducts', 'ctags'], true)) {
        WBZX_Zapal_get_section('catalog', [
            'term' => $term,
            'kicker' => is_string($catalogKicker) ? $catalogKicker : '',
            'catalog_url' => $catalogUrl,
        ]);
    } else {
        WBZX_Zapal_get_section('no-content');
    }
    ?>
</main>
<?php get_footer(); ?>
