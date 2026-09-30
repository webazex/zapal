<?php

namespace WBZX\Zapal\core\services\translate;
use TranslatePermalink;
class TranslateWPAdapter
{
    public static function register():void{
        add_action('after_switch_theme', [TranslateInstaller::class, 'init']);

        add_action('init', [TranslatePermalink::class, 'registerRules']);

        add_filter('rewrite_rules_array', [TranslatePermalink::class, 'rewriteRules']);

        add_filter('request', [TranslatePermalink::class, 'resolveRequest']);
    }
}