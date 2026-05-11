<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    // Forcer HTTPS en production
    public function boot(): void
    {
        if (!app()->isLocal()) {
            URL::forceScheme('https');
        }
    }
}
