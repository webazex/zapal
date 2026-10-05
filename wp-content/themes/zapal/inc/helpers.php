<?php
function WBZX_Zapal_get_section( $section, array $data = [] ): void{
    get_template_part( 'views/sections/' . $section , null , $data);
}

function WBZX_Zapal_get_blocks( $section, array $data = [] ): void{
    get_template_part( 'views/blocks/' . $section , null , $data);
}

function WBZX_Zapal_get_templates($section, array $data = [] ): void{
    get_template_part( 'views/templates/' . $section , null , $data);
}