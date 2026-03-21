<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            // Si des rôles sont spécifiés, vérifier si l'utilisateur a un des rôles
            if (!empty($roles)) {
                if (!in_array($user->role, $roles)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized: Insufficient permissions',
                    ], 403);
                }
            }

            request()->user = $user;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
