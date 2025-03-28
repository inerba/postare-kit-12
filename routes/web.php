<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::prefix(config('postare-kit.pages_prefix'))
    ->middleware(config('postare-kit.middleware', 'web')) // Altrimenti non funzionano i binding
    ->name('cms.')
    ->group(function () {
        Route::get('/{slug}', Controllers\PageController::class)
            ->where('slug', '(.*)')
            ->name('page');
    })->scopeBindings();
