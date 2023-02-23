<?php

use FXC\Base\Helpers\BaseHelper;
use FXC\PluginManagement\Http\Controllers\PluginManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('{locale}/'.BaseHelper::getAdminPrefix())
    ->where(['locale' => array_to_condition(config('panel.locales'))])
    ->middleware(['auth', 'verified'])->group(function () {
        Route::group(['namespace' => 'FXC\PluginManagement\Http\Controllers', 'middleware' => ['web']], function () {
            Route::group(['prefix' => 'plugins'], function () {
                Route::get('', [PluginManagementController::class, 'index'])->name('plugins.index');
                Route::group(['middleware' => 'preventDemo', 'permission' => 'plugins.index',], function () {
                    Route::put('status', [PluginManagementController::class, 'update'])->name('plugins.change.status');
                    Route::delete('{plugin}', [PluginManagementController::class, 'destroy'])->name('plugins.remove');
                });
            });
        });
    });
