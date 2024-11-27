<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\JWTService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Container;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    public function __construct(
        protected RequestInterface $request,
        protected ResponseInterface $response,
        protected Container $container
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): PsrResponseInterface
    {
        $token = $this->request->getHeader('Authorization');

        if (empty($token)) {
            return $this->response->json(['error' => 'Token de autenticação ausente'])->withStatus(401);
        }

        try {
            $jwtService = JWTService::getInstance();
            $decoded = $jwtService->verifyToken($token[0]);
            $this->container->set('user', $decoded);
        } catch (\Exception $e) {
            return $this->response->json(['error' => 'Token de autenticação inválido'])->withStatus(401);
        }

        return $handler->handle($request);
    }
}
