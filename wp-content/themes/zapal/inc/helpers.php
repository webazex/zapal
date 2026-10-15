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
            $acfData = get_field('data-product', $product->ID);
            $category = get_the_terms($product, 'product_cat');
            if(is_wp_error($category) OR !$category){
                $category = [];
            }
            $tags = get_the_terms($product, 'product_tag');
            if(is_wp_error($tags) OR !$tags){
                $tags = [];
            }
            $dataProduct = [
                'id' => $product->ID,
                'name' => $product->post_title,
                'content' => $product->post_content,
                'thumbnail' => (!empty(get_the_post_thumbnail( $product->ID,'large', ['class' => 'some'])))?
                    get_the_post_thumbnail( $product->ID,'large', ['class' => 'some']): false,
                'meta' => (!empty($acfData))? $acfData : false,
                'category' => $category,
                'tags' => $tags

            ];
            $ret[$product->ID] = WBZX_Zapal_mapping_product($dataProduct);
        }
    }
    return $ret;
}

/**
 * Sanitizes ACF WYSIWYG content intended to be rendered inside H1/H2.
 *
 * Allowed editor markup:
 * - <br>
 * - <em>
 * - <strong>
 *
 * ACF/TinyMCE may wrap text in <p>. Paragraph wrappers are accepted only
 * during sanitization and then normalized to <br>, because <p> inside a
 * heading is invalid HTML.
 *
 * After sanitization <em> receives the controlled .serif class used by the
 * existing ZAPAL heading styles. Attributes supplied by the editor itself
 * are still stripped by wp_kses().
 */
function WBZX_Zapal_heading_content($content): string
{
    if (!is_string($content) || trim($content) === '') {
        return '';
    }

    $content = wp_kses($content, [
        'p' => [],
        'br' => [],
        'em' => [],
        'strong' => [],
    ]);

    $content = preg_replace('/<\/p>\s*<p>/i', '<br>', $content);
    $content = str_replace(['<p>', '</p>'], '', $content);
    $content = str_replace('<em>', '<em class="serif">', $content);

    return trim($content);
}
