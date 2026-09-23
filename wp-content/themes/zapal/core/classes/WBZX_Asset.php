<?php

namespace WBZX\zapal\core\assets;

use WP_Error;

class WBZX_Asset
{
    static private function is_exsist(string $path): bool
    {
        return file_exists($path);
    }

    static private function check_asset(string $path): bool | WP_Error {
        if(!self::is_exsist($path)) {
            return new WP_Error('1', "Asset file not found: $path");
        }elseif (is_readable($path)) {
            return true;
        }else{
            return new WP_Error('0', "Asset file is not readable: $path");
        }
    }

    static public function css(string $handle, $paths, array $deps = [], bool | string $ver = false, $media = 'all'): void
    {
        if(self::check_asset($paths)) {
            wp_enqueue_style('zapal-'.$handle, $paths, $deps, $ver);
        }
    }

    static public function js(string $handle, $paths, array $deps = [], bool | string $ver = false, array $args = []):void
    {
        if(self::check_asset($paths)) {
            wp_enqueue_script('zapal-'.$handle, $paths, $deps, $ver, $args);
        }
    }
}