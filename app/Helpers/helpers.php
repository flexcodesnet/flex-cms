<?php

use App\Helpers\ExchangeRate;
use App\Models\Permission;
use App\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

if (!function_exists('role_permission_check')) {
    function role_permission_check($request)
    {
        if (!is_null(auth()->user()) && isset($request)) {
            if (gettype($request) == 'object') {
                $route_name = $request->route()->getName();
//                $route_name = $request->routeIs('panel.index') ? 'panel.dashboard' : $route_name;
                return Permission::check($route_name);
            }

            if (gettype($request) == 'string') {
                $route_name = $request;
                return Permission::check($route_name);
            }

            if (gettype($request) == 'array') {
                $result = false;
                foreach ($request as $route_name) {
                    $result = $result || Permission::check($route_name);
                }
                return $result;
            }
        }

        return false;
    }
}

if (!function_exists('route_merge_params')) {
    function route_merge_params($name, $array)
    {
        return route($name, array_merge(request()->route()->parameters, request()->except('page'), $array));
    }
}

if (!function_exists('array_to_condition')) {
    function array_to_condition($array)
    {
        $temp = '';
        foreach ($array as $item) {
            $temp = sprintf('%s|%s', $temp, $item);
        }
        return $temp;
    }
}

if (!function_exists('array_to_enum')) {
    function array_to_enum($array)
    {
        $temp = '';
        foreach ($array as $item) {
            $temp = sprintf('%s,%s', $temp, $item);
        }
        return $temp;
    }
}

if (!function_exists('tagify_to_values')) {
    function tagify_to_values($content)
    {
        if (!isset($content)) return null;

        $temp = '';
        $content = json_decode($content);
        if (isset($content) && !empty($content)) {
            foreach ($content as $key => $item) {
                $temp = sprintf('%s,%s', $item->value, $temp);
            }
        }

        $temp = trim($temp, ", \t\n\r\0\x0B");
        return $temp;
    }
}

if (!function_exists('remove_null')) {
    function remove_null($array)
    {
        $temp = [];
        foreach ($array as $item) {
            if (!is_null($item))
                $temp[] = $item;
        }
        return $temp;
    }
}

if (!function_exists('get_translated_route')) {
    function get_translated_route($locale)
    {
        return get_translated_routes()[$locale];
    }
}

if (!function_exists('get_translated_routes')) {
    function get_translated_routes()
    {
        $temp = [];
        foreach (config('app.locales') as $local) {
            $params = [];
            if (!is_null(request()->route()))
                $params = request()->query();

            if (isset($params))
                $params['locale'] = $local;

            $baseRoute = 'web.index';
            if (Str::contains(request()->path(), '/panel')) {
                $baseRoute = 'panel.index';
            }

            $temp[$local] = route($baseRoute, array_merge(request()->route()->parameters, $params));

            if (!is_null(request()->route()))
                $temp[$local] = route((request()->route()->getName() !== null) ? request()->route()->getName() : $baseRoute, array_merge(request()->route()->parameters, $params));
        }
        return $temp;
    }
}

if (!function_exists('get_currencies_routes')) {
    function get_currencies_routes()
    {
        $query = request()->query();
        $temp = [];
        foreach (config('app.currencies') as $currency) {
            $query['currency'] = $currency;
            $temp[$currency] = request()->fullUrlWithQuery($query);
        }
        return $temp;
    }
}

if (!function_exists('back_route_name')) {
    function back_route_name()
    {
        return app('router')->getRoutes()->match(app('request')->create(back()->getTargetUrl()))->getName();
    }
}

if (!function_exists('url_is')) {
    function url_is(...$patterns)
    {
        $url = request()->url();

        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $url)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('server_load')) {
    function server_load()
    {
        $os = strtolower(PHP_OS);
        if ($os == 'linux') {
//            if (file_exists('/proc/loadavg')) {
//                $load = file_get_contents('/proc/loadavg');
//                $load = explode(' ', $load, 1);
//                $load = $load[0];
//            } elseif (function_exists('shell_exec')) {
//                $load = explode(' ', `uptime`);
//                $load = $load[count($load) - 1];
//            } else {
//                return false;
//            }
//
//            if (function_exists('shell_exec'))
//                $cpu_count = shell_exec('cat /proc/cpuinfo | grep processor | wc -l');
//
//            return array('load' => $load, 'procs' => $cpu_count);
            return sys_getloadavg()[0];
        } elseif ($os == 'winnt') {
            if (class_exists('COM')) {
                $wmi = new COM('WinMgmts:\\\\.');
                $cpus = $wmi->InstancesOf('Win32_Processor');
                $load = 0;
                $cpu_count = 0;
                if (version_compare('4.50.0', PHP_VERSION) == 1) {
                    while ($cpu = $cpus->Next()) {
                        $load += $cpu->LoadPercentage;
                        $cpu_count++;
                    }
                } else {
                    foreach ($cpus as $cpu) {
                        $load += $cpu->LoadPercentage;
                        $cpu_count++;
                    }
                }
                return array('load' => $load, 'procs' => $cpu_count)['load'];
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('memory_usage')) {
    function memory_usage($real = false) // MB
    {
        return round(((memory_get_peak_usage($real) / 1024) / 1024), 2);
//        return round(((memory_get_usage($real) / 1024) / 1024), 2);
    }
}

if (!function_exists('disk_free_space_usage')) {
    function disk_free_space_usage($real = false) // GB
    {
        return ((disk_free_space("/") / 1024) / 1024) / 1024;
    }
}

if (!function_exists('disk_total_space_usage')) {
    function disk_total_space_usage($real = false) // GB
    {
        return ((disk_total_space("/") / 1024) / 1024) / 1024;
    }
}

if (!function_exists('exchange_rate')) {

    function exchange_rate($value, $base_currency, $quote_currency)
    {
        return ExchangeRate::instance()->exchangeRate($value, $base_currency, $quote_currency);
    }
}

if (!function_exists('my_storage_path')) {
    /**
     * Get the path to the storage path.
     *
     * @param string $path
     * @return string
     */
    function my_storage_path($path = '')
    {
        return public_path(sprintf('storage' . DIRECTORY_SEPARATOR . '%s', $path));
    }
}

if (!function_exists('storage_symbolic_link')) {
    /**
     * Get the path to the storage symbolic link.
     *
     * @param string $path
     * @return string
     */
    function storage_symbolic_link($path = '')
    {
        return (sprintf('.' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . '%s', $path));
    }
}

if (!function_exists('is_landscape')) {
    function is_landscape($file)
    {
        list($width, $height) = getimagesize($file);
        return $width > $height;
    }
}

if (!function_exists('route_is_defined')) {
    function route_is_defined($name)
    {
        return Route::has($name);
    }
}

if (!function_exists('asset_version')) {
    function asset_version($path)
    {
        try {
            return asset($path) . '?v=' . File::lastModified(public_path($path));
        } catch (Exception $ex) {
            return asset($path);
        }
    }
}
