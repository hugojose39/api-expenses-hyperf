<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Hyperf\HttpMessage\Server\Response;

abstract class AbstractController
{
    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected ResponseInterface $response;

    public function getAuthenticatedUser(): User|ResponseInterface
    {
        $user = $this->container->get('user');

        if (!$user) {
            return $this->response->json(['message' => 'Usuário não autenticado'], 401);
        }

        $userModel = User::where('uuid', $user->uuid)->first();

        return $userModel;
    }
}
