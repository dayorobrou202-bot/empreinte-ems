<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request as HttpRequest;

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

        // Configure trusted proxies so that $request->getClientIp() returns the real client IP
        try {
            $proxies = env('TRUSTED_PROXIES', null);
            if ($proxies !== null) {
                $proxiesArray = array_map('trim', explode(',', $proxies));
            } else {
                // If not set, trust the proxy headers (useful behind load balancers) - be cautious in public infra
                $proxiesArray = ['*'];
            }

            // Use Symfony constants via the Request class
            HttpRequest::setTrustedProxies($proxiesArray, HttpRequest::HEADER_X_FORWARDED_ALL);
        } catch (\Throwable $e) {
            Log::warning('AppServiceProvider: impossible de configurer les proxies de confiance: ' . $e->getMessage());
        }
    }
}
