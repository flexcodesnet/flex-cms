<?php

use FXC\Base\Helpers\BaseHelper;
use Illuminate\Support\Arr;

if (!function_exists('platform_path')) {
    /**
     * @param  string|null  $path
     * @return string
     */
    function platform_path(?string $path = null): string
    {
        return base_path('platform/'.$path);
    }
}

if (!function_exists('core_path')) {
    /**
     * @param  string|null  $path
     * @return string
     */
    function core_path(?string $path = null): string
    {
        return platform_path('core/'.$path);
    }
}

if (!function_exists('package_path')) {
    /**
     * @param  string|null  $path
     * @return string
     */
    function package_path(?string $path = null): string
    {
        return platform_path('packages/'.$path);
    }
}

if (!function_exists('get_cms_version')) {
    /**
     * @return string
     */
    function get_cms_version(): string
    {
        $version = '...';

        try {
            $core = BaseHelper::getFileData(core_path('core.json'));

            return Arr::get($core, 'version', $version);
        } catch (Exception $exception) {
            return $version;
        }
    }
}

if (!function_exists('get_core_version')) {
    /**
     * @return string
     */
    function get_core_version(): string
    {
        $version = '...';

        try {
            $core = BaseHelper::getFileData(core_path('core.json'));

            return Arr::get($core, 'coreVersion', $version);
        } catch (Exception $exception) {
            return $version;
        }
    }
}

if (!function_exists('is_in_admin')) {
    /**
     * @param  bool  $force
     * @return bool
     */
    function is_in_admin(bool $force = false): bool
    {
        $prefix = BaseHelper::getAdminPrefix();

        $segments = array_slice(request()->segments(), 0, count(explode('/', $prefix)));

        $isInAdmin = implode('/', $segments) === $prefix;

        return $force ? $isInAdmin : apply_filters(IS_IN_ADMIN_FILTER, $isInAdmin);
    }
}