<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\FormHistory;

class FormHistoryLogger
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only log state-changing form requests from web
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $next($request);
        }

        // Avoid logging for asset or internal routes
        $path = $request->path();
        if (str_starts_with($path, '_') || str_starts_with($path, 'api')) {
            return $next($request);
        }

        // Collect payload excluding sensitive fields
        $data = $request->except(['_token', '_method', 'password', 'password_confirmation']);

        // Replace uploaded files with their original names
        foreach ($request->files->all() as $key => $file) {
            if (is_array($file)) {
                $names = [];
                foreach ($file as $f) {
                    $names[] = $f ? $f->getClientOriginalName() : null;
                }
                $data[$key] = $names;
            } else {
                $data[$key] = $file ? $file->getClientOriginalName() : null;
            }
        }

        try {
            FormHistory::create([
                'actor_id' => auth()->id() ?? null,
                'form_name' => $request->route()?->getName() ?? $request->path(),
                'route' => $request->path(),
                'payload' => $data,
            ]);
        } catch (\Exception $e) {
            Log::warning('FormHistoryLogger: impossible d\'enregistrer l\'historique: ' . $e->getMessage());
        }

        return $next($request);
    }
}
