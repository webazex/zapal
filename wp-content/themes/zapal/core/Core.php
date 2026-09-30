<?php

namespace WBZX\Zapal\core;
use WBZX\Zapal\core\services\translate\Translate;
final class Core
{
    public static function init(){
       Translate::init();
    }
}