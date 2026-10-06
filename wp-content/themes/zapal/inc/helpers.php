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

//parse acf data in field acf_fc_layouts
function WBZX_Zapal_parse_layouts_name(array $data = [] ): string | bool{
    return (!empty($data['acf_fc_layout'])) ? $data['acf_fc_layout'] : false;
}

//get layout name, collate with section name. fi section not exsist - return false
function WBZX_Zapal_collate_section_with_layout(array $pair): string | false {
    /*TODO: something wrong */
}
//get layoutName & render necessary section
function WBZX_Zapal_render_section(string $nameSection): void{

}