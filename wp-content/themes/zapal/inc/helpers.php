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
        WBZX_Zapal_get_section($item['acf_fc_layout'], $item);
    }
}