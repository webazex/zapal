<?php

get_header();

$term = get_queried_object();

// Reuse the same language-aware catalog page as header and breadcrumbs.
$catalogPage = WBZX_Zapal_catalog_page();
$catalogId = $catalogPage instanceof WP_Post ? $catalogPage->ID : 0;
$catalogKicker = ($catalogId && function_exists('get_field'))
    ? get_field('catalog-kicker', $catalogId)
    : '';
$catalogBottomRight = ($catalogId && function_exists('get_field'))
    ? get_field('catalog-bottom-right', $catalogId)
    : '';
$catalogUrl = WBZX_Zapal_catalog_url();
?>
<main>
    <?php
    if ($term instanceof WP_Term && in_array($term->taxonomy, ['cproducts', 'ctags'], true)) {
        WBZX_Zapal_get_section('catalog', [
            'term' => $term,
            'kicker' => is_string($catalogKicker) ? $catalogKicker : '',
            'bottom_right' => is_string($catalogBottomRight) ? $catalogBottomRight : '',
            'catalog_url' => $catalogUrl,
        ]);
    } else {
        WBZX_Zapal_get_section('no-content');
    }
    ?>
</main>
<?php get_footer(); ?>
