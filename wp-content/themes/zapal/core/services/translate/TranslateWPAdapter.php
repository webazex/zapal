<?php

namespace WBZX\Zapal\core\services\translate;

class TranslateWPAdapter
{
    public static function register():void{
        add_action('after_switch_theme', [TranslateInstaller::class, 'run']);

        add_action('init', [TranslatePermalink::class, 'registerRules']);

        add_filter('rewrite_rules_array', [TranslatePermalink::class, 'registerTag']);

        add_filter('request', [TranslatePermalink::class, 'resolveRequest']);
    }
}