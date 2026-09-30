<?php

namespace WBZX\Zapal;
use WBZX\Zapal\core\Core as Core;
class App
{
    private static object $core;
    public static function init(){
        Core::init();
    }
}