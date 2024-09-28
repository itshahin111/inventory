<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



class JwtToken
{
    public static function createToken($userEmail): string
    {
        $key = env("JWT_Key");
        $payload = [
            "iss" => "laravel-token",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "userEmail" => $userEmail
        ];
        return JWT::encode($payload, $key, "HS256");
    }

    public static function verifyToken($token)
    {
        try {
            $key = env("JWT_KEY");
            $decode = JWT::decode($token, new Key($key, 'HS256'));
        } catch (Exception $exception) {
            return 'unauthorized';
        }
    }
}