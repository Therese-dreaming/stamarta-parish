<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Simple gate alias for ministry head access (admin or ministry_head)
        Gate::define('access-ministry', function ($user) {
            return in_array($user->role, ['admin', 'ministry_head']);
        });
    }
}
