<?php

get_header();

$data = get_field('page');

?>
<main>
    <?php
    if (is_array($data) && $data) {
        WBZX_Zapal_render_section($data);
    } elseif (have_posts()) {
        while (have_posts()) {
            the_post();
            the_content();
        }
    }
    ?>
</main>
<?php get_footer(); ?>
