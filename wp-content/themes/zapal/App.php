<?php

namespace WBZX\Zapal;
use WBZX\Zapal\core\Core;
final class App
{
    private static bool $loaded = false;
    //private static object $core;
    public static function init(){
        if(self::$loaded){
           return;
        }
        Core::init();
        self::$loaded = true;
    }
}