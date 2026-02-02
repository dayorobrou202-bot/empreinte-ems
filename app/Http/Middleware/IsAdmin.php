<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            // Rediriger vers le dashboard collaborateur si ce n'est pas un admin
            return redirect()->route('collaborateur.dashboard')
                ->with('error', 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
