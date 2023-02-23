<?php

use FXC\Base\Models\Permission;
use FXC\Base\Supports\CacheKey;
use FXC\Base\Supports\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

if (!function_exists('role_permission_check')) {
    /**
     * @param $request
     * @return bool
     */
    function role_permission_check($request): bool
    {
        if (!is_null(auth()->user()) && isset($request)) {
            if (gettype($request) == 'object') {
                $route_name = $request->route()->getName();
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
    /**
     * @param $name
     * @param $array
     * @return string
     */
    function route_merge_params($name, $array): string
    {
        return route($name, array_merge(request()->route()->parameters, request()->except('page'), $array));
    }
}

if (!function_exists('array_to_condition')) {
    /**
     * @param $array
     * @return string
     */
    function array_to_condition($array): string
    {
        $temp = '';
        foreach ($array as $item) {
            $temp = sprintf('%s|%s', $temp, $item);
        }

        return trim($temp, '|');
    }
}

if (!function_exists('array_to_enum')) {
    /**
     * @param $array
     * @return string
     */
    function array_to_enum($array): string
    {
        $temp = '';
        foreach ($array as $item) {
            $temp = sprintf('%s,%s', $temp, $item);
        }
        return $temp;
    }
}

if (!function_exists('tagify_to_values')) {
    /**
     * @param $content
     * @return string|null
     */
    function tagify_to_values($content): ?string
    {
        if (!isset($content)) {
            return null;
        }

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
    /**
     * @param $array
     * @return array
     */
    function remove_null($array): array
    {
        $temp = [];
        foreach ($array as $item) {
            if (!is_null($item)) {
                $temp[] = $item;
            }
        }
        return $temp;
    }
}

if (!function_exists('get_translated_route')) {
    /**
     * @param $locale
     * @return string
     */
    function get_translated_route($locale): string
    {
        $temp = "";
        $params = [];
        if (!is_null(request()->route())) {
            $params = request()->route()->parameters();
        }

        if (isset($params)) {
            $params['locale'] = $locale;
        }

        $baseRoute = 'web.index';
        if (Str::contains(request()->path(), '/panel')) {
            $baseRoute = 'panel.index';
        }

        $temp = route($baseRoute, array_merge($params, request()->query()));

        if (!is_null(request()->route())) {
            $temp = route((request()->route()->getName() !== null) ? request()->route()->getName() : $baseRoute, array_merge($params, request()->query()));
        }

        return $temp;
    }
}

if (!function_exists('get_translated_routes')) {
    /**
     * @return array
     */
    function get_translated_routes(): array
    {
        $temp = [];
        foreach (config('app.locales') as $local) {
            $params = [];
            if (!is_null(request()->route())) {
                $params = request()->route()->parameters();
            }

            if (isset($params)) {
                $params['locale'] = $local;
            }

            $baseRoute = 'web.index';
            if (Str::contains(request()->path(), '/panel')) {
                $baseRoute = 'panel.index';
            }

            $temp[$local] = route($baseRoute, array_merge($params, request()->query()));

            if (!is_null(request()->route())) {
                $temp[$local] = route((request()->route()->getName() !== null) ? request()->route()->getName() : $baseRoute, array_merge($params, request()->query()));
            }
        }
        return $temp;
    }
}

if (!function_exists('get_currencies_routes')) {
    /**
     * @return array
     */
    function get_currencies_routes(): array
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
    /**
     * @return mixed
     */
    function back_route_name(): mixed
    {
        return app('router')->getRoutes()->match(app('request')->create(back()->getTargetUrl()))->getName();
    }
}

if (!function_exists('url_is')) {
    /**
     * @param ...$patterns
     * @return bool
     */
    function url_is(...$patterns): bool
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
    /**
     * @return mixed
     */
    function server_load(): mixed
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
    /**
     * @param $real
     * @return float
     */
    function memory_usage($real = false): float // MB
    {
        return round(((memory_get_peak_usage($real) / 1024) / 1024), 2);
//        return round(((memory_get_usage($real) / 1024) / 1024), 2);
    }
}

if (!function_exists('disk_free_space_usage')) {
    /**
     * @param $real
     * @return float|int
     */
    function disk_free_space_usage($real = false) // GB
    {
        return ((disk_free_space("/") / 1024) / 1024) / 1024;
    }
}

if (!function_exists('disk_total_space_usage')) {
    /**
     * @param $real
     * @return float|int
     */
    function disk_total_space_usage($real = false) // GB
    {
        return ((disk_total_space("/") / 1024) / 1024) / 1024;
    }
}

if (!function_exists('exchange_rate')) {

    /**
     * @param $value
     * @param $base_currency
     * @param $quote_currency
     * @return mixed
     */
    function exchange_rate($value, $base_currency, $quote_currency)
    {
        return ExchangeRate::instance()->exchangeRate($value, $base_currency, $quote_currency);
    }
}

if (!function_exists('my_storage_path')) {
    /**
     * Get the path to the storage path.
     *
     * @param  string  $path
     * @return string
     */
    function my_storage_path(string $path = ''): string
    {
        return public_path(sprintf('storage'.DIRECTORY_SEPARATOR.'%s', $path));
    }
}

if (!function_exists('storage_symbolic_link')) {
    /**
     * Get the path to the storage symbolic link.
     *
     * @param  string  $path
     * @return string
     */
    function storage_symbolic_link(string $path = ''): string
    {
        return (sprintf('.'.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'%s', $path));
    }
}

if (!function_exists('is_landscape')) {
    /**
     * @param $file
     * @return bool
     */
    function is_landscape($file): bool
    {
        list($width, $height) = getimagesize($file);
        return $width > $height;
    }
}

if (!function_exists('route_is_defined')) {
    /**
     * @param $name
     * @return bool
     */
    function route_is_defined($name): bool
    {
        return Route::has($name);
    }
}

if (!function_exists('asset_version')) {
    /**
     * @param $path
     * @return string
     */
    function asset_version($path): string
    {
        $path = asset($path);
        $version = file_version($path);
        return "{$path}?v={$version}";
    }
}
/*---------------------------------------{</>}---------------------------------------*/
if (!function_exists('file_version')) {
    /**
     * @param $path
     * @return string
     */
    function file_version($path): string
    {
        if (File::exists(public_path($path))) {
            return File::lastModified(public_path($path));
        }
        return '1.0.0';
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('get_cached_key_data')) {
    /**
     * @param $cache_key
     * @param  Closure  $data
     * @param $ttl  // ttl: time
     * @return mixed
     */
    function get_cached_key_data($cache_key, Closure $data, $ttl = null)
    {
        if (!Cache::has($cache_key)) {
            if ($ttl) {
                Cache::remember($cache_key, $ttl, $data);
            } else {
                Cache::rememberForever($cache_key, $data);
            }
        }

        return Cache::get($cache_key);
    }
}
/*---------------------------------------{</>}---------------------------------------*/
if (!function_exists('my_unread_notifications')) {

    /**
     * @return mixed|null
     */
    function my_unread_notifications()
    {
        if (auth()->check()) {
            $cache_key = CacheKey::user_unread_notifications();

            return get_cached_key_data($cache_key, function () {
                $notifications = auth()->user()->unreadNotifications()->take(10)->get();
                if ($notifications and count($notifications)) {
                    $notifications = collect($notifications)->map(function ($notification) {
                        return [
                            'id'              => $notification->id,
                            'type'            => $notification->type,
                            'notifiable_type' => $notification->notifiable_type,
                            'notifiable_id'   => $notification->notifiable_id,
                            'read_at'         => $notification->read_at,
                            'data'            => [
                                'id'         => $notification->data['id'] ?? null,
                                'title'      => $notification->data['title'] ?? null,
                                'icon'       => $notification->data['icon'] ?? null,
                                'link'       => $notification->data['link'] ?? null,
                                'created_at' => $notification->data['created_at'] ?? null,
                            ],
                        ];
                    });
                }
                return $notifications;
            });
        }

        return null;
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('get_list_option')) {
    /**
     * @param $listName
     * @return mixed
     */
    function get_list_option($listName)
    {
        return ListOption::cachedList($listName);
    }
}

/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('get_panel_module')) {
    /**
     * @param $moduleName
     * @param $key
     * @return Repository|Application|mixed
     */
    function get_panel_module($moduleName, $key)
    {
        $moduleName = str_replace('-', '_', $moduleName);

        return config("panel.modules.{$moduleName}.{$key}");
    }
}

/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('clean_special_chars')) {
    /**
     * @param $string
     * @return array|string|string[]
     */
    function clean_special_chars($string)
    {
        $string = str_replace(' ', '-', $string);                // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return str_replace('-', '', $string);
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('upload_image')) {
    /**
     * @param      $request
     * @param      $field_name
     * @param      $folder
     * @param  null  $old_image
     *
     * @return string
     */
    function upload_image($request, $field_name, $folder, $old_image = null): string
    {
        // get file extension
        $extension = $request->file($field_name)->getClientOriginalExtension();

        // generate filename to store
        $hash = md5(time()).Str::random(3).'_'.rand(100, 999).Str::random(3);

        $fileNameToStore = $hash.'.'.$extension;

        // delete old image
        if ($old_image) {
            unlink_old_file($old_image, $folder);
        }
        // check if folder exist
        if (!Storage::exists(storage_path($folder))) {
            Storage::makeDirectory(storage_path($folder));
        }
        $request->file($field_name)->storeAs($folder, $fileNameToStore, 'public');

        return $fileNameToStore;
    }
}
/*---------------------------------------{</>}---------------------------------------*/
if (!function_exists('unlink_old_file')) {
    /**
     * for delete file from directory
     *
     * @param $fieldName  ( obj->image )
     * @param $public_file_dir  ('storage/FOLDERNAME')
     */
    function unlink_old_file($fieldName, $public_file_dir)
    {
        // get file source
        if ($fieldName && $fieldName != '') {
            $oldPath = $public_file_dir.'/'.$fieldName;
            if (Storage::disk('public')->exists($oldPath)) {
                // delete old file from storage
                Storage::disk('public')->delete($oldPath);
            }
        }
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('group_by')) {

    /**
     * @param $key
     * @param $data
     * @return array
     */
    function group_by($key, $data): array
    {
        $result = [];

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('group_by_cols')) {

    /**
     * @param $keys
     * @param $data
     * @return array
     */
    function group_by_cols($keys, $data): array
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        $result = [];
        foreach ($keys as $index => $key) {
            $results = group_by($key, $data);
            foreach ($results as $group => $group_data) {
                if (isset($keys[$index + 1])) {
                    $result[$group] = group_by($keys[$index + 1], $group_data);
                } else {
                    break;
                }
            }
        }

        return $result;
    }

}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('get_fields_section_with_cols')) {
    /**
     * @param $data
     * @return array
     */
    function get_fields_section_with_cols($data): array
    {
        return group_by_cols(['section', 'section_col'], $data);
    }

}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('slug')) {
    /**
     * @param $value
     * @param  string  $separator
     * @return string
     */
    function slug($value, string $separator = '-'): string
    {
        if (is_null($value)) {
            return "";
        }

        $value = trim($value);

        $value = mb_strtolower($value, "UTF-8");

        $value = preg_replace("/[^a-z0-9_\sءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $value);

        $value = preg_replace("/[\s-]+/", " ", $value);

        $value = preg_replace("/[\s_]/", $separator, $value);

        return $value;
    }

}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('currencies')) {

    /**
     * @return mixed
     */
    function currencies()
    {
        $cache_key = CacheKey::active_cached('currencies');
        return get_cached_key_data($cache_key, function () {
            return Currency::active()->get();
        });
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('latestCategories')) {

    /**
     * @return mixed
     */
    function latestCategories()
    {
        $limit = $limit ?? 10;
        return Category::take($limit)->latest()->withCount('posts')->get();
//        return get_cached_key_data($cache_key, function () {
//            return Currency::active()->get();
//        });
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('latestArticles')) {

    /**
     * @return mixed
     */
    function latestArticles($limit = null)
    {
        $limit = $limit ?? 10;
        return Post::take($limit)->latest()->get();
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('get_locales')) {

    /**
     * @return mixed
     */
    function get_locales()
    {
        return array_keys(LaravelLocalization::getSupportedLocales());
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('LocalizedUrl')) {

    /**
     * @return false|string
     */
    function LocalizedUrl($url, $locale = null)
    {
        return LaravelLocalization::getLocalizedUrl($locale, $url);
    }
}
/*---------------------------------------{</>}---------------------------------------*/

if (!function_exists('show_datetime')) {

    /**
     * @return false|string
     */
    function show_datetime($datetime, $type = 'blog')
    {
        $format = 'd F Y';
        return Carbon\Carbon::parse($datetime)->format($format);
    }
}
/*---------------------------------------{</>}---------------------------------------*/
