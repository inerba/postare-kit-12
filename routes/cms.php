<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('postare-kit.middleware', 'web'),
    'as' => 'cms.',
], function () {

    // Rotte dei Posts
    Route::prefix(config('postare-kit.posts_prefix', 'blog'))->group(function () {
        Route::get('/', Controllers\Blog\BlogHomepageController::class)->name('blog.home');
        Route::get('{category:slug}', Controllers\Blog\BlogCategoryController::class)->name('blog.category');
        Route::get('/{category:slug}/{post:slug}', Controllers\Blog\PostController::class)->name('blog.post');
    });

    // Rotte delle pagine CMS sempre per ultime
    Route::get('/{slug}', Controllers\PageController::class)
        ->where('slug', '(.*)')
        ->name('page');
})->scopeBindings();
