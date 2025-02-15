<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow unauthenticated access to /api/login
        if ($request->is('api/login')) {
            return $next($request);
        }

        if ($request->bearerToken() && Auth::guard('sanctum')->user()) {
            Auth::setUser(Auth::guard('sanctum')->user());
            return $next($request);
        } else {
            return response()->json([
                'code' => JsonResponse::HTTP_UNAUTHORIZED,
                'message' => 'Unauthorized'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }
}
