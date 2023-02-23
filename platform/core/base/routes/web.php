<?php

use FXC\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

$modules = include(core_path('base/config/modules.php'));

Route::prefix('{locale}/panel')
    ->where(['locale' => array_to_condition(config('panel.locales'))])
    ->middleware(['auth', 'verified', 'role_permission'])
    ->group(function () use ($modules) {
        foreach ($modules as $module) {
            $module = (object) $module;
            $controller_name = $module->controller_name;
            $module_name = $module->module_name;
            Route::prefix($module->module_name)->group(function () use ($controller_name, $module_name) {
                Route::get('/', [$controller_name, 'index'])->name("{$module_name}.index");
                Route::get('add', [$controller_name, 'add'])->name("{$module_name}.add");
                Route::prefix('{id}')->group(function () use ($controller_name, $module_name) {
                    Route::get('show', [$controller_name, 'show'])->name("{$module_name}.show");
                    Route::get('edit', [$controller_name, 'edit'])->name("{$module_name}.edit");
                    Route::middleware(['ajax'])->group(function () use ($controller_name, $module_name) {
                        Route::post('create', [$controller_name, 'create'])->name("{$module_name}.children.model.create");
                        Route::put('update', [$controller_name, 'update'])->name("{$module_name}.update");
                        Route::delete('delete', [$controller_name, 'delete'])->name("{$module_name}.delete");
                    });
                });
            });
        }
    });

Route::group(['namespace' => 'FXC\Base\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'system/info'], function () {
            Route::match(['GET', 'POST'], '', [
                'as'         => 'system.info',
                'uses'       => 'SystemController@getInfo',
                'permission' => 'superuser',
            ]);
        });

        Route::group(['prefix' => 'system/cache'], function () {
            Route::get('', [
                'as'         => 'system.cache',
                'uses'       => 'SystemController@getCacheManagement',
                'permission' => 'superuser',
            ]);

            Route::post('clear', [
                'as'         => 'system.cache.clear',
                'uses'       => 'SystemController@postClearCache',
                'permission' => 'superuser',
                'middleware' => 'preventDemo',
            ]);
        });

        Route::post('membership/authorize', [
            'as'         => 'membership.authorize',
            'uses'       => 'SystemController@authorize',
            'permission' => false,
        ]);

        Route::get('menu-items-count', [
            'as'         => 'menu-items-count',
            'uses'       => 'SystemController@getMenuItemsCount',
            'permission' => false,
        ]);

        Route::get('system/check-update', [
            'as'         => 'system.check-update',
            'uses'       => 'SystemController@getCheckUpdate',
            'permission' => 'superuser',
        ]);

        Route::get('system/updater', [
            'as'         => 'system.updater',
            'uses'       => 'SystemController@getUpdater',
            'permission' => 'superuser',
        ]);

        Route::post('system/updater', [
            'as'         => 'system.updater.post',
            'uses'       => 'SystemController@getUpdater',
            'permission' => 'superuser',
            'middleware' => 'preventDemo',
        ]);
    });

    Route::get('settings-language/{alias}', [SystemController::class, 'getLanguage'])->name('settings.language');
});
