<?php
get_header();
$data = function_exists('get_field') ? get_field('page') : [];
?>
<main>
    <?php
        //WBZX_Zapal_get_data_products();
        if(is_array( $data ) && $data) {
            WBZX_Zapal_render_section($data);
        }else{
            WBZX_Zapal_get_section('no-content');
        }
    ?>
</main>
<?php get_footer();?>

