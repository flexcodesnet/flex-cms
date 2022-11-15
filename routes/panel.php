<?php

use App\Http\Controllers\Panel\AuthController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Panel\PermissionsController;
use App\Http\Controllers\Panel\RolesController;
use App\Http\Controllers\Panel\SettingsController;
use App\Http\Controllers\Panel\UsersController;
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
Route::prefix('{locale}')->where(['locale' => array_to_condition(config('app.locales'))])->group(function () {
    Route::middleware([])->prefix('panel')->group(function () {
        Route::middleware(['guest'])->get('login', [AuthController::class, 'login'])->name('login');
        Route::middleware(['guest'])->any('auth', [AuthController::class, 'authenticate'])->name('auth');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');

            Route::middleware(['role_permission'])->group(function () {
                Route::get('', [DashboardController::class, 'index'])->name('index');
                Route::any('settings', [SettingsController::class, 'index'])->name('settings.index');

                Route::prefix('users')->group(function () {
                    Route::get('', [UsersController::class, 'index'])->name('users.index');
                    Route::middleware(['ajax'])->get('data', [UsersController::class, 'data'])->name('users.data');
                    Route::get('add', [UsersController::class, 'add'])->name('users.add');
                    Route::middleware(['ajax'])->post('create', [UsersController::class, 'create'])->name('users.create');

                    Route::prefix('{id}')->group(function () {
                        Route::get('show', [UsersController::class, 'show'])->name('users.show');
                        Route::get('edit', [UsersController::class, 'edit'])->name('users.edit');
                        Route::middleware(['ajax'])->put('update', [UsersController::class, 'update'])->name('users.update');
                        Route::middleware(['ajax'])->delete('delete', [UsersController::class, 'delete'])->name('users.delete');
                    });
                });

                Route::prefix('roles')->group(function () {
                    Route::get('', [RolesController::class, 'index'])->name('roles.index');
                    Route::middleware(['ajax'])->get('data', [RolesController::class, 'data'])->name('roles.data');
                    Route::get('add', [RolesController::class, 'add'])->name('roles.add');
                    Route::middleware(['ajax'])->post('create', [RolesController::class, 'create'])->name('roles.create');

                    Route::prefix('{id}')->group(function () {
                        Route::get('show', [RolesController::class, 'show'])->name('roles.show');
                        Route::get('edit', [RolesController::class, 'edit'])->name('roles.edit');
                        Route::middleware(['ajax'])->put('update', [RolesController::class, 'update'])->name('roles.update');
                        Route::middleware(['ajax'])->delete('delete', [RolesController::class, 'delete'])->name('roles.delete');
                    });
                });

                Route::prefix('permissions')->group(function () {
                    Route::get('', [PermissionsController::class, 'index'])->name('permissions.index');
                    Route::middleware(['ajax'])->get('data', [PermissionsController::class, 'data'])->name('permissions.data');
                    Route::get('add', [PermissionsController::class, 'add'])->name('permissions.add');
                    Route::middleware(['ajax'])->post('create/{id?}', [PermissionsController::class, 'create'])->name('permissions.create');

                    Route::prefix('{id}')->group(function () {
                        Route::get('show', [PermissionsController::class, 'show'])->name('permissions.show');
                        Route::get('edit', [PermissionsController::class, 'edit'])->name('permissions.edit');
                        Route::get('add', [PermissionsController::class, 'add'])->name('permissions.children.model.add');
                        Route::middleware(['ajax'])->post('create', [PermissionsController::class, 'create'])->name('permissions.children.model.create');
                        Route::middleware(['ajax'])->put('update', [PermissionsController::class, 'update'])->name('permissions.update');
                        Route::middleware(['ajax'])->delete('delete', [PermissionsController::class, 'delete'])->name('permissions.delete');
                    });
                });
            });
        });
    });
});
