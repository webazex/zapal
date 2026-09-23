<?php
use WBZX\zapal\core\assets\WBZX_Asset as Asset;
function zapal_get_sources() {
    require_once CORE_CLASSES_DIR.'WBZX_Asset.php';
    Asset::css('main', get_stylesheet_uri());
    Asset::js('main', ASSET_DIR.'js/main.js', ['jquery'], false, ['in_footer' => true]);
}
add_action('wp_enqueue_scripts', 'zapal_get_sources');