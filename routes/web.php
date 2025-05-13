<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => trim(LaravelLocalization::setLocale() . '/' . config('postare-kit.pages_prefix'), '/'),
    'middleware' => config('postare-kit.middleware', 'web'),
    'as' => 'cms.',
], function () {
    Route::get('/', function () {
        return view('home');
    });
    Route::get('/{slug}', Controllers\PageController::class)
        ->where('slug', '(.*)')
        ->name('page');
})->scopeBindings();
