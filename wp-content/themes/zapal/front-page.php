<?php
get_header();
$data = get_field('page');
?>
<main>
    <?php
        if(is_array( $data ) && $data) {
            WBZX_Zapal_render_section($data);
        }else{
            WBZX_Zapal_get_section('no-content');
        }
    ?>
</main>
<?php get_footer();?>

