<?php

get_header();

?>
<main>
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();

            $productId = get_the_ID();
            $products = WBZX_Zapal_products([
                'p' => $productId,
                'posts_per_page' => 1,
                'post_status' => current_user_can('edit_post', $productId)
                    ? get_post_status($productId)
                    : 'publish',
            ]);
            $product = $products[$productId] ?? null;

            if (!is_array($product)) {
                WBZX_Zapal_get_section('no-content');
                continue;
            }

            $acfData = is_array($product['data'] ?? null)
                ? $product['data']
                : [];

            WBZX_Zapal_get_section('product-hero', $product);
            WBZX_Zapal_get_section('product-gallery', $product);
            WBZX_Zapal_get_section('product-story', $product);
            WBZX_Zapal_get_section('product-spec', $product);

            $delivery = is_array($acfData['delivers-group'] ?? null)
                ? $acfData['delivers-group']
                : [];

            $delivery['deliveries'] = is_array($product['deliveries'] ?? null)
                ? $product['deliveries']
                : [];

            if (!empty($delivery['h2-content']) || $delivery['deliveries']) {
                WBZX_Zapal_get_section('delivery', $delivery);
            }

            $contacts = is_array($acfData['contacts'] ?? null)
                ? $acfData['contacts']
                : [];

            $contacts['selected_product_id'] = $productId;

            WBZX_Zapal_get_section('contact', $contacts);
        }
    } else {
        WBZX_Zapal_get_section('no-content');
    }
    ?>
</main>
<?php get_footer(); ?>
