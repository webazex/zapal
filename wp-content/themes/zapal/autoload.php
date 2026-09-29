<?php
require_once get_template_directory() . '/inc/constants.php';
spl_autoload_register(static function (string $class): void {
    $prefix = 'WBZX\\Zapal\\';
    $baseDir = get_template_directory() . '/core/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));

    $file = $baseDir
        . str_replace('\\', '/', $relativeClass)
        . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});