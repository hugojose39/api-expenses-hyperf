<?php

namespace App\Middleware;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Container;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use function Hyperf\Support\env;

class AuthMiddleware
{
    protected $jwtSecretKey;

    public function __construct(protected RequestInterface $request, protected ResponseInterface $response, protected Container $container)
    {
        $this->jwtSecretKey = env('JWT_SECRET_KEY');
    }

    public function process($request, $handler)
    {
        $token = $this->request->getHeader('Authorization');
        
        if (empty($token)) {
            return $this->response->json(['error' => 'Token de autenticação ausente'], 401);
        }
        
        try {
            $decoded = JWT::decode($token[0], new Key($this->jwtSecretKey, 'HS256'));
            $this->container->set('user', $decoded);
        } catch (\Exception $e) {
            return $this->response->json(['error' => 'Token de autenticação inválido'], 401);
        }
        
        return $handler->handle($request);
    }
    
}