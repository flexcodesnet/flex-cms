<?php

use App\Http\Controllers\Panel\AuthController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Panel\SettingController;
use FXC\Base\Http\Controllers\PermissionController;
use FXC\Base\Http\Controllers\RoleController;
use FXC\Base\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cms Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// panel shortcut
Route::get('panel', function () {
    return redirect(route('panel.index', app()->getLocale()));
});

Route::middleware(['guest'])->get('panel/login', function () {
    return redirect(route('panel.login', app()->getLocale()));
});
// end panel shortcut
Route::prefix('{locale}')->where(['locale' => array_to_condition(config('panel.locales'))])->group(function () {
    Route::middleware([])->prefix('panel')->group(function () {
        Route::middleware(['guest'])->get('login', [AuthController::class, 'login'])->name('login');
        Route::middleware(['guest'])->any('auth', [AuthController::class, 'authenticate'])->name('auth');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');

            Route::middleware(['role_permission'])->group(function () {
                Route::get('', [DashboardController::class, 'index'])->name('index');
                Route::any('settings', [SettingController::class, 'index'])->name('settings.index');

                $modules = config('panel.modules', []);

                foreach ($modules as $module) {
                    $module = (object) $module;
                    $moduleName = $module->name;
                    $moduleUrl = str_replace('_', '-', $module->name);
                    $controllerName = $module->controller;
                    Route::prefix($moduleUrl)->group(function () use ($moduleName, $controllerName) {
                        Route::get('', [$controllerName, 'index'])->name("{$moduleName}.index");
                        Route::get('add', [$controllerName, 'add'])->name("{$moduleName}.add");

                        Route::prefix('{id}')->group(function () use ($moduleName, $controllerName) {
                            Route::get('show', [$controllerName, 'show'])->name("{$moduleName}.show");
                            Route::get('edit', [$controllerName, 'edit'])->name("{$moduleName}.edit");
                        });
                        Route::middleware(['ajax'])->group(function () use ($moduleName, $controllerName) {
                            Route::get('data', [$controllerName, 'data'])->name("{$moduleName}.data");
                            Route::post('create', [$controllerName, 'create'])->name("{$moduleName}.create");
                            Route::put('{id}/update', [$controllerName, 'update'])->name("{$moduleName}.update");
                            Route::delete('{id}/delete', [$controllerName, 'delete'])->name("{$moduleName}.delete");
                        });

                    });
                }
            });

            Route::middleware([])->group(function () {
                $modules = config('panel.modules', []);
                foreach ($modules as $module) {
                    $module = (object) $module;
                    $moduleName = $module->name;
                    $moduleUrl = str_replace('_', '-', $module->name);
                    $controllerName = $module->controller;
                    Route::prefix($moduleUrl)->group(function () use ($moduleName, $controllerName) {
                        Route::get('{id}/seo', [$controllerName, 'seo'])->name("{$moduleName}.seo");
                        Route::middleware(['ajax'])->group(function () use ($moduleName, $controllerName) {
                            Route::post('{id}/seo/update', [$controllerName, 'seoUpdate'])->name("{$moduleName}.seo.update");
                        });
                    });
                }
            });

            Route::middleware(['role_permission'])->group(function () {
                Route::prefix('users')->group(function () {
                    Route::get('', [UserController::class, 'index'])->name('users.index');
                    Route::middleware(['ajax'])->get('data', [UserController::class, 'data'])->name('users.data');
                    Route::get('add', [UserController::class, 'add'])->name('users.add');
                    Route::middleware(['ajax'])->post('create', [UserController::class, 'create'])->name('users.create');

                    Route::prefix('{id}')->group(function () {
                        Route::get('show', [UserController::class, 'show'])->name('users.show');
                        Route::get('edit', [UserController::class, 'edit'])->name('users.edit');
                        Route::middleware(['ajax'])->put('update', [UserController::class, 'update'])->name('users.update');
                        Route::middleware(['ajax'])->delete('delete', [UserController::class, 'delete'])->name('users.delete');
                    });
                });

                Route::prefix('roles')->group(function () {
                    Route::get('', [RoleController::class, 'index'])->name('roles.index');
                    Route::middleware(['ajax'])->get('data', [RoleController::class, 'data'])->name('roles.data');
                    Route::get('add', [RoleController::class, 'add'])->name('roles.add');
                    Route::middleware(['ajax'])->post('create', [RoleController::class, 'create'])->name('roles.create');

                    Route::prefix('{id}')->group(function () {
                        Route::get('show', [RoleController::class, 'show'])->name('roles.show');
                        Route::get('edit', [RoleController::class, 'edit'])->name('roles.edit');
                        Route::middleware(['ajax'])->put('update', [RoleController::class, 'update'])->name('roles.update');
                        Route::middleware(['ajax'])->delete('delete', [RoleController::class, 'delete'])->name('roles.delete');
                    });
                });

                Route::prefix('permissions')->group(function () {
                    Route::get('', [PermissionController::class, 'index'])->name('permissions.index');
                    Route::middleware(['ajax'])->get('data', [PermissionController::class, 'data'])->name('permissions.data');
                    Route::get('add', [PermissionController::class, 'add'])->name('permissions.add');
                    Route::middleware(['ajax'])->post('create/{id?}', [PermissionController::class, 'create'])->name('permissions.create');

                    Route::prefix('{id}')->group(function () {
                        Route::get('show', [PermissionController::class, 'show'])->name('permissions.show');
                        Route::get('edit', [PermissionController::class, 'edit'])->name('permissions.edit');
                        Route::get('add', [PermissionController::class, 'add'])->name('permissions.children.model.add');
                        Route::middleware(['ajax'])->post('create', [PermissionController::class, 'create'])->name('permissions.model.create');
                        Route::middleware(['ajax'])->put('update', [PermissionController::class, 'update'])->name('permissions.update');
                        Route::middleware(['ajax'])->delete('delete', [PermissionController::class, 'delete'])->name('permissions.delete');
                    });
                });
            });

            Route::middleware(['ajax'])->delete('{module}/{id}/image/delete', [AdminController::class, 'imageDelete'])->name('image.delete');

        });
    });
});