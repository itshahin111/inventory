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

        $result = JwtToken::VerifyToken($token);

        // Check if the token verification failed
        if ($result == "unauthorized") {
            return redirect('/uerLogin');
        } else {
            $request->headers->set("email", $result->userEmail);
            $request->headers->set("id", $result->userId);
            return $next($request);
        }
    }
}