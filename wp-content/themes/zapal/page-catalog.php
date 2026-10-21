<?php
/**
 * Template Name: Каталог продукції
 *
 * The /catalog/ page is resolved automatically by this filename. Translated
 * pages can use the same template via the WordPress page template selector.
 */

get_header();

$pageTitle = get_the_title(get_queried_object_id());
$pageExcerpt = get_the_excerpt(get_queried_object_id());
?>
<main>
    <?php
    WBZX_Zapal_get_section('catalog', [
        'title' => $pageTitle ?: __('Продукція', 'zapal'),
        'description' => $pageExcerpt ?: __('Соуси та професійні рішення для HoReCa.', 'zapal'),
    ]);
    ?>
</main>
<?php get_footer(); ?>
