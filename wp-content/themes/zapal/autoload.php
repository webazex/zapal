<?php

spl_autoload_register(static function (string $class): void {
    $prefix = 'WBZX\\Zapal\\';
    $baseDir = get_template_directory();

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $cleanStr = str_replace('WBZX\\', '', $relativeClass);
    $file = $baseDir.'/'.str_replace('\\', '/', $cleanStr).'.php';
    if (is_file($file)) {
        require_once $file;
    }
});