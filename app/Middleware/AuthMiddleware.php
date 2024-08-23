<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Container;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function Hyperf\Support\env;

class AuthMiddleware
{
    private string $jwtSecretKey;

    public function __construct(
        protected RequestInterface $request,
        protected ResponseInterface $response,
        protected Container $container
    ) {
        $this->jwtSecretKey = env('JWT_SECRET_KEY', '');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): PsrResponseInterface
    {
        $token = $this->request->getHeader('Authorization');

        if (empty($token)) {
            return $this->response->json(['error' => 'Token de autenticação ausente'])->withStatus(401);
        }

        try {
            $decoded = JWT::decode($token[0], new Key($this->jwtSecretKey, 'HS256'));
            $this->container->set('user', $decoded);
        } catch (\Exception $e) {
            return $this->response->json(['error' => 'Token de autenticação inválido'])->withStatus(401);
        }

        return $handler->handle($request);
    }
}
