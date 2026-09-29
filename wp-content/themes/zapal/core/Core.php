<?php

namespace WBZX\Zapal\core;

final class Core
{
    private static object $translate;
    private static object $seo;
    private static object $customfields;

    public static function init(){
        //inited private property
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