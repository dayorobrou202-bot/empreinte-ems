<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

// Ensure middleware is registered into the web group
use App\Http\Middleware\FormHistoryLogger;

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
        try {
            // Push the FormHistoryLogger into the 'web' middleware group so all web form submissions are logged
            $this->app->make('router')->pushMiddlewareToGroup('web', FormHistoryLogger::class);
        } catch (\Exception $e) {
            Log::warning('AppServiceProvider: impossible d\'enregistrer le middleware FormHistoryLogger: ' . $e->getMessage());
        }
    }
}
