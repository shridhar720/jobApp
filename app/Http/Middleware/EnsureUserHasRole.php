<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! in_array($request->user()?->role, $roles)) {
            return response()->json([
                'message' => 'Forbidden. Required role: ' . implode(' or ', $roles),
            ], 403);
        }

        return $next($request);
    }
}
