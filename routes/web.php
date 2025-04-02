<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

/**
 * Rotte per le pagine statiche
 * Se non utilizzi un prefisso, assicurati che queste rotte siano sempre dichiarate alla fine
 * per evitare conflitti con altre rotte già definite.
 */
Route::prefix(config('postare-kit.pages_prefix'))
    ->middleware(config('postare-kit.middleware', 'web')) // Altrimenti non funzionano i binding
    ->name('cms.')
    ->group(function () {
        Route::get('/{slug}', Controllers\PageController::class)
            ->where('slug', '(.*)')
            ->name('page');
    })->scopeBindings();
