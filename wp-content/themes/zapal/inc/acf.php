<?php

function WBZX_Zapal_acf_heading_toolbars(array $toolbars): array
{
    $toolbars['ZAPAL Heading'] = [
        1 => [
            'bold',
            'italic',
            'forecolor',
            'removeformat',
            'undo',
            'redo',
        ],
    ];

    return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars', 'WBZX_Zapal_acf_heading_toolbars');
