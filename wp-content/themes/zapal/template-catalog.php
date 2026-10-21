<?php
/**
 * Template Name: Каталог продукції
 *
 * Assign this template to each localized catalog page in WordPress.
 */

get_header();

$pageId = get_queried_object_id();
$pageTitle = get_the_title($pageId);
$pageExcerpt = get_the_excerpt($pageId);
$kicker = function_exists('get_field') ? get_field('catalog-kicker', $pageId) : '';
$bottomRight = function_exists('get_field') ? get_field('catalog-bottom-right', $pageId) : '';
$customTitle = function_exists('get_field') ? get_field('catalog-title', $pageId) : '';
$customDescription = function_exists('get_field') ? get_field('catalog-description', $pageId) : '';
?>
<main>
    <?php
    WBZX_Zapal_get_section('catalog', [
        'kicker' => is_string($kicker) ? $kicker : '',
        'bottom_right' => is_string($bottomRight) ? $bottomRight : '',
        'title' => $customTitle ?: ($pageTitle ?: __('Продукція', 'zapal')),
        'description' => $customDescription ?: $pageExcerpt,
    ]);
    ?>
</main>
<?php get_footer(); ?>
