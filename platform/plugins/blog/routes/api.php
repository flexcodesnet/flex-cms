<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix'     => 'api/v1',
    'namespace'  => 'FXC\Blog\Http\Controllers\API',
], function () {
    Route::get('search', [PostController::class, 'getSearch']);

    Route::prefix('posts')->group(function () {
        Route::get('', [PostController::class, 'index']);
        Route::get('/filters', [PostController::class, 'getFilters']);
        Route::get('/{slug}', [PostController::class, 'findBySlug']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('', [CategoryController::class, 'index']);
        Route::get('/filters', [CategoryController::class, 'getFilters']);
        Route::get('/{slug}', [CategoryController::class, 'findBySlug']);
    });

    Route::prefix('tags')->group(function () {
        Route::get('', [TagController::class, 'index']);
    });


});
