<?php

namespace App\Http\Middleware;

use App\Helper\JwtToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie("token");
        $result = JwtToken::verifyToken($token);
        if ($result == "unauthorized") {
            return response()->json([
                "status" => "success",
                "message" => "Unauthorized"
            ], 401);
        } else {
            $request->headers->set("email", $request);
            return $next($request);
        }
    }
}