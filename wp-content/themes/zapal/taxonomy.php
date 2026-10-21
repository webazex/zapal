<?php

get_header();

$term = get_queried_object();
?>
<main>
    <?php
    if ($term instanceof WP_Term && in_array($term->taxonomy, ['cproducts', 'ctags'], true)) {
        WBZX_Zapal_get_section('catalog', ['term' => $term]);
    } else {
        WBZX_Zapal_get_section('no-content');
    }
    ?>
</main>
<?php get_footer(); ?>
