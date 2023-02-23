<?php

use Illuminate\Support\Facades\File;

if (!function_exists('plugin_path')) {
    /**
     * @param ?string  $path
     * @return string
     */
    function plugin_path(?string $path = null): string
    {
        return platform_path('plugins'.DIRECTORY_SEPARATOR.$path);
    }
}

if (!function_exists('is_plugin_active')) {
    /**
     * @param  string  $alias
     * @return bool
     */
    function is_plugin_active(string $alias): bool
    {
        if (!in_array($alias, get_active_plugins())) {
            return false;
        }

        $path = plugin_path($alias);

        return File::isDirectory($path) && File::exists($path.'/plugin.json');
    }
}

if (!function_exists('get_active_plugins')) {
    /**
     * @return array
     */
    function get_active_plugins(): array
    {
//        try {
//            return array_unique(json_decode(setting('activated_plugins', '[]'), true));
//        } catch (Exception $exception) {
            return [];
//        }
    }
}
