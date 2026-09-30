<?php

namespace WBZX\Zapal\core\services\translate;

class Translate
{
    private static array $languages = [];
    private static string $currentLanguage;
    public static function init():void {
        self::$currentLanguage = self::getMainLanguage();

        $langFile = get_template_directory() . '/lang.php';

        if (!is_file($langFile)) {
            wp_die('Language config not found');
        }

        self::$languages = require $langFile;
    }
    public static function getMainLanguage():string {
        return get_option('zapal_main_lang', 'ua');
    }

    public static function getLanguages():array
    {
        return self::$languages;
    }

    public static function getCurrentLanguage():string{
        return self::$currentLanguage;
    }

    public static function setCurrentLanguage(string $language): void
    {
        if (!array_key_exists($language, self::$languages)) {
            return;
        }

        self::$currentLanguage = $language;
    }
}