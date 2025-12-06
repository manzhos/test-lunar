<?php

namespace App\Providers;

use Lunar\Admin\Support\Facades\LunarPanel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Lunar's Filament panel early so a default panel exists for console commands.
        LunarPanel::register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
