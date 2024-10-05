<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



class JwtToken
{
    public static function createToken($userEmail, $userId): string
    {
        $key = env("JWT_Key");
        $payload = [
            "iss" => "laravel-token",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "userEmail" => $userEmail,
            "userId" => $userId
        ];
        return JWT::encode($payload, $key, "HS256");
    }
    public static function setPasswordToken($userEmail)
    {
        $key = env("JWT_KEY");
        $payload = [
            "iss" => "laravel-token-password",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "userEmail" => $userEmail,
            "userId" => "0"
        ];
        return JWT::encode($payload, $key, "HS256");
    }

    public static function verifyToken($token)
    {
        try {
            if ($token == null) {
                return 'unauthorized';
            } else {
                $key = env("JWT_KEY");
                $decode = JWT::decode($token, new Key($key, 'HS256'));
                return $decode; // Return the decoded token
            }

        } catch (Exception $exception) {
            return 'unauthorized';
        }
    }

}