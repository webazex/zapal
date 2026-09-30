<?php

namespace WBZX\zapal\core\services\translate;

class Translate
{
    private static array $languages = [];
    private static string $currentLanguage;
    public static function init():void {
        self::$currentLanguage = self::getMainLanguage();

        if(file_exists(get_template_directory() . 'lang.php')){
            self::$languages = require get_template_directory() . 'lang.php';
        }else{
            wp_die();
        }
        TranslateWPAdapter::register();
    }
    public static function getMainLanguage():string {
        return get_option('zapal_main_lang', 'ua');
    }

    public static function getLanguages():array
    {
        return self::$languages;
    }
}