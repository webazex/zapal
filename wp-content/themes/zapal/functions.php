<?php
//load zapal constants
require_once get_template_directory().DIRECTORY_SEPARATOR.'inc'.DIRECTORY_SEPARATOR.'constants.php';

//cleaned wp trash css&js
require_once WBZX_ZAPAL_INC_DIR.'cleanup.php';

//mappers
require_once WBZX_ZAPAL_BACK_DIR.'mappers.php';

//helpers
require_once WBZX_ZAPAL_INC_DIR.'helpers.php';

//ACF UI/config
require_once WBZX_ZAPAL_INC_DIR.'acf.php';

//security
require_once WBZX_ZAPAL_INC_DIR.'security.php';

//add css&js
require_once WBZX_ZAPAL_INC_DIR.'sources.php';

//register menus
require_once WBZX_ZAPAL_INC_DIR.'menu.php';

//register taxonomies & cpt
add_action('init', function (){
    require_once WBZX_ZAPAL_INC_DIR.'taxonomy'.DIRECTORY_SEPARATOR.'product-categories.php';
    require_once WBZX_ZAPAL_INC_DIR.'cpt'.DIRECTORY_SEPARATOR.'products.php';
    require_once WBZX_ZAPAL_INC_DIR.'cpt'.DIRECTORY_SEPARATOR.'recipes.php';
});

//add ajax handlers
require_once WBZX_ZAPAL_BACK_DIR. 'ajax.php';
