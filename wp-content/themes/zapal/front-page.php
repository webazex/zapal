<?php
get_header();
$data = get_field('page');
?>
<pre>
    <?php print_r($data); ?>
</pre>
<main>
    <?php
        WBZX_Zapal_get_section('hero-frontpage');
        WBZX_Zapal_get_section('manifesto');
        WBZX_Zapal_get_section('products');
        WBZX_Zapal_get_section('use-section');
        WBZX_Zapal_get_section('package-b2b');
        WBZX_Zapal_get_section('delivery');
        WBZX_Zapal_get_section('about');
        WBZX_Zapal_get_section('contact');
    ?>
</main>
<?php get_footer();?>

