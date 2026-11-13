<?php

namespace WBZX\zapal\core\services\translate;

class TranslateService
{
    public static function init():void {
        TranslateWPAdapter::register();
    }
}