<?php
function WBZX_Zapal_get_section( $section, array $data = [] ): void{
    get_template_part( 'views/sections/' . $section , null , $data);
}

function WBZX_Zapal_get_blocks( $block, array $data = [] ): void{
    get_template_part( 'views/blocks/' . $block , null , $data);
}

function WBZX_Zapal_get_templates($template, array $data = [] ): void{
    get_template_part( 'views/templates/' . $template , null , $data);
}

function WBZX_Zapal_render_section(array $data): void{
    foreach ($data as $item) {
        $layoutKey = $item['acf_fc_layout'] ?? null;
        if(!$layoutKey){
            continue;
        }
        WBZX_Zapal_get_section($layoutKey, $item);
    }
}

function WBZX_Zapal_get_query(array $args = []):object {
    $defaultArgs['posts_per_page'] = get_option('posts_per_page');
    $args = wp_parse_args($args, $defaultArgs);
    return new WP_Query( $args );
}
function WBZX_Zapal_get_products(array $args = []): array {
    $ret = [];
    $defaultArgs['post_type'] = 'products';
    $args = wp_parse_args($args, $defaultArgs);
    $obj = WBZX_Zapal_get_query($args);
    if($obj->have_posts()){
        $ret = $obj->posts;
    }
    return $ret;
}

function WBZX_Zapal_products(){
    $ret = [];
    $products = WBZX_Zapal_get_products();
    if(!empty($products)){
        foreach($products as $product){
            $dataProduct = [
                'id' => $product->ID,
                'content' => $product->post_content,
                'thumbnail' => get_the_post_thumbnail( $product->ID,'large', ['class' => 'some']),
                'meta' => get_field('data-product', $product->ID),

            ];
            $ret[$product->ID] = WBZX_Zapal_mapping_product($dataProduct);
        }
    }
    return $ret;
}

