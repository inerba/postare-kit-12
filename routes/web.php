<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Rotte per il CMS, sempre per ultime
require __DIR__.'/cms.php';
