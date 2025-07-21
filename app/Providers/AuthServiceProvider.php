<?php

namespace App\Providers;

use App\Policies;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Devo inserire qui le policy per i plugin di terze parti che voglio far funzionare con shield
        // dopo averli inseriti devo anche lanciare il comando php artisan shield:generate --all
        Gate::policy(\BezhanSalleh\FilamentExceptions\Models\Exception::class, Policies\ExceptionPolicy::class);
        Gate::policy(\Spatie\Activitylog\Models\Activity::class, Policies\ActivityPolicy::class);
    }
}
