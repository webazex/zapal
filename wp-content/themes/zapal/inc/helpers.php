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

function WBZX_Zapal_get_data_products(array $args = []): array {
    $ret = [];
    $args['posts_per_page'] = get_option('posts_per_page');
    $args['post_type'] = 'products';
    $obj = new WP_Query( $args );
    if($obj->have_posts()){
        $ret = $obj->posts;
    }
    return $ret;
}

function WBZX_Zapal_mapping_products(array $data):array {
    $ret = [];
    foreach ($data as $item) {
        var_dump($item);
    }
    return $ret;
}

