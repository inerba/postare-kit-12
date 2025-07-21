<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('postare-kit.middleware', 'web'),
    'as' => 'cms.',
], function () {

    // Rotte del blog
    Route::prefix('blog')->group(function () {
        Route::get('/', Controllers\Blog\BlogHomepageController::class)->name('blog.home');
        Route::get('/category/{category:slug}', Controllers\Blog\BlogCategoryController::class)->name('blog.category');
        Route::get('/post/{post:slug}', Controllers\Blog\PostController::class)->name('blog.post');
    });

    // Rotte delle pagine CMS sempre per ultime
    Route::get('/{slug}', Controllers\PageController::class)
        ->where('slug', '(.*)')
        ->name('page');
})->scopeBindings();
