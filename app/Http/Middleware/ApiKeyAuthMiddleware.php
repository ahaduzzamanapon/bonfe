<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');
        if ($apiKey && $apiKey === 'woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389') { // Replace with your actual API key
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
