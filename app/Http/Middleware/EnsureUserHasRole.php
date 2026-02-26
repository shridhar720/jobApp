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
        // allow passing descriptive names as well as numeric values
        $map = [
            'applicant' => '1',
            'employer'  => '2',
        ];

        $normalized = array_map(function ($role) use ($map) {
            return $map[$role] ?? $role;
        }, $roles);

        $userRole = (string) $request->user()?->role;

        if (! in_array($userRole, $normalized)) {
            return response()->json([
                'message' => 'Forbidden. Required role: ' . implode(' or ', $roles),
            ], 403);
        }

        return $next($request);
    }
}
