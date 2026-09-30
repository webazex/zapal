<?php

namespace WBZX\Zapal;
use WBZX\Zapal\core\Core;
final class App
{
    private static bool $loaded = false;
    public static function init(): void
    {
        if (self::$loaded) {
            return;
        }

        self::$loaded = true;

        Core::init();
    }
}