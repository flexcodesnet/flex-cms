<?php

namespace FXC\Base\Providers;

use FXC\Base\Helpers\BaseHelper;
use FXC\Base\Supports\Helper;
use FXC\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setNamespace('core/base')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general']);

        $this->app['config']->set([
            'session.cookie'                   => 'botble_session',
            'ziggy.except'                     => ['debugbar.*'],
            'app.debug_blacklist'              => [
                '_ENV'    => [
                    'APP_KEY',
                    'ADMIN_DIR',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'REDIS_PASSWORD',
                    'MAIL_PASSWORD',
                    'PUSHER_APP_KEY',
                    'PUSHER_APP_SECRET',
                ],
                '_SERVER' => [
                    'APP_KEY',
                    'ADMIN_DIR',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'REDIS_PASSWORD',
                    'MAIL_PASSWORD',
                    'PUSHER_APP_KEY',
                    'PUSHER_APP_SECRET',
                ],
                '_POST'   => [
                    'password',
                ],
            ],
            'datatables-buttons.pdf_generator' => 'excel',
            'excel.exports.csv.use_bom'        => true,
            'dompdf.public_path'               => public_path(),
            'debugbar.enabled'                 => $this->app['config']->get('app.debug') && !$this->app->runningInConsole() && !$this->app->environment(['testing', 'production']),
        ]);

//        $this->app->bind('path.lang', function () {
//            return base_path('lang');
//        });
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
//            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->publishAssets();

        Schema::defaultStringLength(191);

        $config = $this->app['config'];


        $this->app->booted(function () use ($config) {
        });

        Paginator::useBootstrap();

        $forceUrl = $config->get('core.base.general.force_root_url');
        if (!empty($forceUrl)) {
            URL::forceRootUrl($forceUrl);
        }

        $forceSchema = $config->get('core.base.general.force_schema');
        if (!empty($forceSchema)) {
            $this->app['request']->server->set('HTTPS', 'on');

            URL::forceScheme($forceSchema);
        }

        $this->configureIni();

        $config->set([
            'purifier.settings'                           => array_merge(
                $config->get('purifier.settings', []),
                $config->get('core.base.general.purifier', [])
            ),
            'laravel-form-builder.defaults.wrapper_class' => 'form-group mb-3',
            'database.connections.mysql.strict'           => $config->get('core.base.general.db_strict_mode'),
        ]);

        if (!$config->has('logging.channels.deprecations')) {
            $config->set([
                'logging.channels.deprecations' => [
                    'driver' => 'single',
                    'path'   => storage_path('logs/php-deprecation-warnings.log'),
                ],
            ]);
        }
    }

    protected function configureIni()
    {
        $currentLimit = ini_get('memory_limit');
        $currentLimitInt = Helper::convertHrToBytes($currentLimit);

        $memoryLimit = $this->app['config']->get('core.base.general.memory_limit');

        // Define memory limits.
        if (!$memoryLimit) {
            if (false === Helper::isIniValueChangeable('memory_limit')) {
                $memoryLimit = $currentLimit;
            } else {
                $memoryLimit = '64M';
            }
        }

        // Set memory limits.
        $limitInt = Helper::convertHrToBytes($memoryLimit);
        if (-1 !== $currentLimitInt && (-1 === $limitInt || $limitInt > $currentLimitInt)) {
            BaseHelper::iniSet('memory_limit', $memoryLimit);
        }
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [];
//        return [BreadcrumbsManager::class];
    }
}
