<?php
use WBZX\zapal\core\services\translate\TranslatePermalink as TranslatePermalink;
add_action('init', [TranslatePermalink::class, 'registerPermalinks']);
add_filter('rewrite_rules_array' [TranslatePermalink::class, 'rewriteRules']);
add_action('after_switch_theme', function (){
    flush_rewrite_rules();
});