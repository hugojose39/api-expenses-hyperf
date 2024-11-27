<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use function Hyperf\Support\env;
use stdClass;

class JWTService
{
    private static ?JWTService $instance = null;
    private string $jwtSecretKey;

    public function __construct()
    {
        $this->jwtSecretKey = env('JWT_SECRET_KEY', '');
    }

    public static function getInstance(): JWTService
    {
        if (self::$instance === null) {
            self::$instance = new JWTService();
        }

        return self::$instance;
    }

    public function generateToken(array $payload): string
    {
        return JWT::encode($payload, $this->jwtSecretKey, 'HS256');
    }

    public function verifyToken(string $token): stdClass
    {
        return JWT::decode($token, new Key($this->jwtSecretKey, 'HS256'));
    }
}
