<?php

namespace FXC\PluginManagement\Services;

use FXC\Base\Helpers\BaseHelper;
use FXC\Base\Supports\Helper;
use FXC\PluginManagement\Events\ActivatedPluginEvent;
use Composer\Autoload\ClassLoader;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Setting;

class PluginService
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * PluginService constructor.
     * @param Application $app
     * @param Filesystem $files
     */
    public function __construct(Application $app, Filesystem $files)
    {
        $this->app = $app;
        $this->files = $files;
    }

    /**
     * @param string $plugin
     * @return array
     */
    public function activate(string $plugin): array
    {
        $validate = $this->validate($plugin);

        if ($validate['error']) {
            return $validate;
        }

        $content = BaseHelper::getFileData(plugin_path($plugin) . '/plugin.json');
        if (empty($content)) {
            return [
                'error' => true,
                'message' => trans('packages/plugin-management::plugin.invalid_json'),
            ];
        }

        if (!Arr::get($content, 'ready', 1)) {
            return [
                'error' => true,
                'message' => trans(
                    'packages/plugin-management::plugin.plugin_is_not_ready',
                    ['name' => Str::studly($plugin)]
                ),
            ];
        }

        $activatedPlugins = get_active_plugins();
        if (!in_array($plugin, $activatedPlugins)) {
            if (!empty(Arr::get($content, 'require'))) {
                $valid = count(array_intersect($content['require'], $activatedPlugins)) == count($content['require']);
                if (!$valid) {
                    return [
                        'error' => true,
                        'message' => trans(
                            'packages/plugin-management::plugin.missing_required_plugins',
                            ['plugins' => implode(',', $content['require'])]
                        ),
                    ];
                }
            }

            if (!class_exists($content['provider'])) {
                $loader = new ClassLoader();
                $loader->setPsr4($content['namespace'], plugin_path($plugin . '/src'));
                $loader->register(true);

                $this->app->register($content['provider']);

                if (class_exists($content['namespace'] . 'Plugin')) {
                    call_user_func([$content['namespace'] . 'Plugin', 'activate']);
                }

                $migrationPath = plugin_path($plugin . '/database/migrations');

                if ($this->files->isDirectory($migrationPath)) {
                    $this->app['migrator']->run($migrationPath);
                }

                $published = $this->publishAssets($plugin);

                if ($published['error']) {
                    return $published;
                }
            }

//            Setting::set('activated_plugins', json_encode(array_values(array_merge($activatedPlugins, [$plugin]))))
//                ->save();

            if (class_exists($content['namespace'] . 'Plugin')) {
                call_user_func([$content['namespace'] . 'Plugin', 'activated']);
            }

            Helper::clearCache();

            event(new ActivatedPluginEvent($plugin));

            return [
                'error' => false,
                'message' => trans('packages/plugin-management::plugin.activate_success'),
            ];
        }

        return [
            'error' => true,
            'message' => trans('packages/plugin-management::plugin.activated_already'),
        ];
    }

    /**
     * @param string $plugin
     * @return array
     */
    protected function validate(string $plugin): array
    {
        $location = plugin_path($plugin);

        if (!$this->files->isDirectory($location)) {
            return [
                'error' => true,
                'message' => trans('packages/plugin-management::plugin.plugin_not_exist'),
            ];
        }

        if (!$this->files->exists($location . '/plugin.json')) {
            return [
                'error' => true,
                'message' => trans('packages/plugin-management::plugin.missing_json_file'),
            ];
        }

        return [
            'error' => false,
            'message' => trans('packages/plugin-management::plugin.plugin_invalid'),
        ];
    }

    /**
     * @param string $plugin
     * @return array
     */
    public function publishAssets(string $plugin): array
    {
        $validate = $this->validate($plugin);

        if ($validate['error']) {
            return $validate;
        }

        $pluginPath = public_path('vendor/core/plugins');

        if (!$this->files->isDirectory($pluginPath)) {
            $this->files->makeDirectory($pluginPath, 0755, true);
        }

        if (!$this->files->isWritable($pluginPath)) {
            return [
                'error' => true,
                'message' => trans(
                    'packages/plugin-management::plugin.folder_is_not_writeable',
                    ['name' => $pluginPath]
                ),
            ];
        }

        if ($this->files->isDirectory(plugin_path($plugin . '/public'))) {
            $publishedPath = public_path('vendor/core') . '/' . $this->getPluginNamespace($plugin);
            $this->files->copyDirectory(plugin_path($plugin . '/public'), $publishedPath);
        }

        return [
            'error' => false,
            'message' => trans('packages/plugin-management::plugin.published_assets_success', ['name' => $plugin]),
        ];
    }

    /**
     * @param string $plugin
     * @return array
     */
    public function remove(string $plugin): array
    {
        $validate = $this->validate($plugin);

        if ($validate['error']) {
            return $validate;
        }

        $this->deactivate($plugin);

        $location = plugin_path($plugin);

        $content = [];

        if ($this->files->exists($location . '/plugin.json')) {
            $content = BaseHelper::getFileData($location . '/plugin.json');

            if (!empty($content)) {
                if (!class_exists($content['provider'])) {
                    $loader = new ClassLoader();
                    $loader->setPsr4($content['namespace'], plugin_path($plugin . '/src'));
                    $loader->register(true);
                }

                Schema::disableForeignKeyConstraints();
                if (class_exists($content['namespace'] . 'Plugin')) {
                    call_user_func([$content['namespace'] . 'Plugin', 'remove']);
                }
                Schema::enableForeignKeyConstraints();
            }
        }

        $migrations = [];
        foreach (BaseHelper::scanFolder($location . '/database/migrations') as $file) {
            $migrations[] = pathinfo($file, PATHINFO_FILENAME);
        }

        DB::table('migrations')->whereIn('migration', $migrations)->delete();

        $this->files->deleteDirectory($location);

        if (empty($this->files->directories(plugin_path()))) {
            $this->files->deleteDirectory(plugin_path());
        }

        Helper::removeModuleFiles($plugin, 'plugins');

        $publishedPath = public_path('vendor/core') . '/' . $this->getPluginNamespace($plugin);

        if (File::isDirectory($publishedPath)) {
            File::deleteDirectory($publishedPath);
        }

        if (class_exists($content['namespace'] . 'Plugin')) {
            call_user_func([$content['namespace'] . 'Plugin', 'removed']);
        }

        Helper::clearCache();

        return [
            'error' => false,
            'message' => trans('packages/plugin-management::plugin.plugin_removed'),
        ];
    }

    /**
     * @param string $plugin
     * @return array
     */
    public function deactivate(string $plugin): array
    {
        $validate = $this->validate($plugin);

        if ($validate['error']) {
            return $validate;
        }

        $content = BaseHelper::getFileData(plugin_path($plugin) . '/plugin.json');
        if (empty($content)) {
            return [
                'error' => true,
                'message' => trans('packages/plugin-management::plugin.invalid_json'),
            ];
        }

        if (!class_exists($content['provider'])) {
            $loader = new ClassLoader();
            $loader->setPsr4($content['namespace'], plugin_path($plugin . '/src'));
            $loader->register(true);
        }

        $activatedPlugins = get_active_plugins();
        if (in_array($plugin, $activatedPlugins)) {
            if (class_exists($content['namespace'] . 'Plugin')) {
                call_user_func([$content['namespace'] . 'Plugin', 'deactivate']);
            }

            if (($key = array_search($plugin, $activatedPlugins)) !== false) {
                unset($activatedPlugins[$key]);
            }

            Setting::set('activated_plugins', json_encode(array_values($activatedPlugins)))
                ->save();

            if (class_exists($content['namespace'] . 'Plugin')) {
                call_user_func([$content['namespace'] . 'Plugin', 'deactivated']);
            }

            Helper::clearCache();

            return [
                'error' => false,
                'message' => trans('packages/plugin-management::plugin.deactivated_success'),
            ];
        }

        return [
            'error' => true,
            'message' => trans('packages/plugin-management::plugin.deactivated_already'),
        ];
    }

    /**
     * @param string $plugin
     * @return string
     */
    public function getPluginNamespace(string $plugin): string
    {
        return $this->app['config']->get('core.base.general.plugin_namespaces.' . $plugin, $plugin);
    }
}
