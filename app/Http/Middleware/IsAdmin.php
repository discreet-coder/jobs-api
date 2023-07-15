<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class IsAdmin extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->type != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not allowed',
                    'result' => null
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is Invalid',
                    'result' => null
                ], Response::HTTP_UNAUTHORIZED);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is Expired',
                    'result' => null
                ], Response::HTTP_UNAUTHORIZED);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Authorization Token not found',
                    'result' => null
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        return $next($request);
    }
}
