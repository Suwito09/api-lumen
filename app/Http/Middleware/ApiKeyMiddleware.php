<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Pengguna;

class ApiKeyMiddleware
{
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || !Pengguna::where('api_key', $apiKey)->exists()) {
            return response()->json(['error' => 'Unauthorized or invalid API key'], 401);
        }

        return $next($request);
    }
}
