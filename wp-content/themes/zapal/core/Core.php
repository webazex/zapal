<?php

namespace WBZX\Zapal\core;
use WBZX\zapal\core\services\translate\Translate;
final class Core
{
    private static object $translate;
    private static object $seo;
    private static object $customfields;

    public static function init(){
       Translate::init();
    }

    public static function langApp(){
        return self::$translate;
    }

    public static function seoApp(){
        return self::$seo;
    }

    public static function cfApp(){
        return self::$customfields;
    }
}