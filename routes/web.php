<?php

use App\Http\Controllers;
use App\Http\Controllers\Blog;
use Illuminate\Support\Facades\Route;

// use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Route::group([
//     'prefix' => trim(LaravelLocalization::setLocale() . '/' . config('postare-kit.pages_prefix'), '/'),
//     'middleware' => config('postare-kit.middleware', 'web'),
//     'as' => 'cms.',
// ], function () {
Route::get('/', function () {
    return view('home');
});
Route::get('/{slug}', Controllers\PageController::class)
    ->where('slug', '(.*)')
    ->name('page');

// Rotte del blog
Route::prefix('blog')->group(function () {
    Route::get('/', Blog\BlogHomepageController::class)->name('blog.home');
    Route::get('/category/{category:slug}', Blog\BlogCategoryController::class)->name('blog.category');
    Route::get('/post/{post:slug}', Blog\PostController::class)->name('blog.post');
});
// })->scopeBindings();
